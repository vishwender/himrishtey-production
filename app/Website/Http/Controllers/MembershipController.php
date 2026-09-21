<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\MembershipPlan;
use App\Website\Models\MembershipType;
use App\Website\Models\MemberWallet;
use App\Website\Models\Payment;
use App\Website\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Razorpay\Api\Api;

class MembershipController extends Controller
{
    public function index()
    {
        $data['memberships'] = MembershipType::all();

        // dd($data['memberships']);
        return view('dashboard.memberships.index', compact('data'));
    }

    public function sendSms(Request $request)
    {
        $user = $request->user_id;
        $member = auth()->guard('member')->user();
        $number = User::where('user_type', '5')->pluck('phone');
        $messageText = ' A call request from id '.$member->profile_id.' regarding membership. Call back immediately '.$member->full_name.'.HIMRMB';
        $messageText = urlencode($messageText);
        $url = 'http://nimbusit.biz/api/SmsApi/SendMultipleApi?UserID=himrishteybiz&Password=vqbj8362VQ&SenderID=HIMRMB&Phno='.$number[0].'&Msg='.$messageText.'&EntityID=1701164189692214854&TemplateID=1707166254945835455';
        $response = Http::get($url);

        return $response->body();
    }

    public function plans($id)
    {
        $data['membership'] = MembershipType::where('id', $id)->first();
        $data['plans'] = MembershipPlan::where('membership_type', $id)->get();

        // dd($data['plans']);
        return view('dashboard.memberships.plan', compact('data'));
        // return view('dashboard.layouts.modal', compact('data'));
    }

    public function buyPlan($planId)
    {
        $plan = MembershipPlan::findOrFail($planId);

        $api = new Api(config('website.runtime.razorpay_key'), config('website.runtime.razorpay_secret'));

        $orderData = [
            'receipt' => 'rcpt_'.time(),
            'amount' => (int) round($plan->final_cost * 100),
            'currency' => 'INR',
            'payment_capture' => 1,
        ];

        $order = $api->order->create($orderData);

        return view('dashboard.memberships.checkout', [
            'order_id' => $order['id'],
            'plan' => $plan,
            'razor_key' => config('website.runtime.razorpay_key'),
        ]);
    }
    // public function verifyPayment(Request $request)
    // {
    //     $api = new Api(config('website.runtime.razorpay_key'), config('website.runtime.razorpay_secret'));

    //     try {
    //         $attributes = [
    //             'razorpay_order_id'   => $request->razorpay_order_id,
    //             'razorpay_payment_id' => $request->razorpay_payment_id,
    //             'razorpay_signature'  => $request->razorpay_signature
    //         ];

    //         $api->utility->verifyPaymentSignature($attributes);

    //         $payment = $api->payment->fetch($request->razorpay_payment_id);
    //         if ($payment->status !== 'captured') {
    //             $payment->capture(['amount' => $payment->amount]);
    //         }

    //         $user = auth()->guard('member')->user();
    //         $plan = MembershipPlan::findOrFail($request->plan_id);
    //         $user->plan_id = $plan->id;
    //         $user->save();

    //         DB::connection('site')->table('payments')->insert([
    //             'payment_date' => now(),
    //             'member_id'    => $user->id,
    //             'plan_id'      => $plan->id,
    //             'payment_id'   => $request->razorpay_payment_id,
    //             'amount'       => $payment->amount / 100,
    //             'remarks'      => 'Razorpay',
    //         ]);

    //         return redirect()->route('membership.success')->with('success', 'Payment successful! Membership activated.');
    //     } catch (\Exception $e) {
    //         return redirect()->route('membership.failed')
    //             ->with('error', 'Payment failed: ' . $e->getMessage());
    //     }
    // }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'plan_id' => 'required|integer',
        ]);

        $api = new Api(config('website.runtime.razorpay_key'), config('website.runtime.razorpay_secret'));

        try {

            // 1. Verify Razorpay signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // 2. Fetch payment
            $payment = $api->payment->fetch(
                $request->razorpay_payment_id
            );

            // 3. Verify order belongs to payment
            if ($payment->order_id !== $request->razorpay_order_id) {
                throw new \Exception('Payment order mismatch.');
            }

            // 4. Make sure payment is captured
            if ($payment->status !== 'captured') {

                $payment->capture([
                    'amount' => $payment->amount,
                ]);

                $payment = $api->payment->fetch(
                    $request->razorpay_payment_id
                );
            }

            if ($payment->status !== 'captured') {
                throw new \Exception('Payment was not captured.');
            }

            // 5. Get logged-in member
            $member = auth()->guard('member')->user();

            if (! $member) {
                throw new \Exception('Member not authenticated.');
            }

            // 6. Get selected membership plan
            $plan = MembershipPlan::findOrFail($request->plan_id);

            $expectedAmount = (int) round((float) $plan->final_cost * 100);

            if ((int) $payment->amount !== $expectedAmount) {
                throw new \Exception('Payment amount does not match the selected plan.');
            }

            if (strtoupper((string) $payment->currency) !== 'INR') {
                throw new \Exception('Unsupported payment currency.');
            }

            $walletReward = $plan->walletRewardPoints();

            /*
        |--------------------------------------------------------------------------
        | 7. Update member + save payment together
        |--------------------------------------------------------------------------
        */

            DB::connection('site')->transaction(function () use (
                $member,
                $plan,
                $payment,
                $request,
                $walletReward
            ) {

                // Serialize membership payments for this member so two callbacks
                // cannot both credit the same Razorpay payment.
                DB::connection('site')->table('members')
                    ->where('id', $member->id)
                    ->lockForUpdate()
                    ->first();

                $paymentAlreadyProcessed = DB::connection('site')->table('payments')
                    ->where('payment_id', $request->razorpay_payment_id)
                    ->lockForUpdate()
                    ->exists();

                if ($paymentAlreadyProcessed) {
                    return;
                }

                $activationDate = now()->toDateString();

                // Update the authenticated member explicitly. The legacy members
                // table does not use normal Eloquent timestamps, so a direct update
                // makes the persisted columns unambiguous.
                DB::connection('site')->table('members')
                    ->where('id', $member->id)
                    ->lockForUpdate()
                    ->update([
                        'plan_id' => $plan->id,
                        'plan_activation_date' => $activationDate,
                    ]);

                $activated = DB::connection('site')->table('members')
                    ->where('id', $member->id)
                    ->where('plan_id', $plan->id)
                    ->whereDate('plan_activation_date', $activationDate)
                    ->exists();

                if (! $activated) {
                    throw new \RuntimeException('Membership activation could not be saved.');
                }

                // Save payment transaction
                DB::connection('site')->table('payments')->insert([
                    'payment_date' => now(),

                    'member_id' => $member->id,

                    'plan_id' => $plan->id,

                    'payment_id' => $request->razorpay_payment_id,

                    'amount' => $payment->amount / 100,

                    'remarks' => 'Razorpay',
                ]);

                if ($walletReward > 0) {
                    $lastWallet = MemberWallet::where('member_id', $member->id)
                        ->latest('id')
                        ->lockForUpdate()
                        ->first();

                    $currentBalance = (int) ($lastWallet->wallet_balance ?? 0);

                    MemberWallet::create([
                        'member_id' => $member->id,
                        'amount_added' => $walletReward,
                        'amount_deducted' => 0,
                        'wallet_balance' => $currentBalance + $walletReward,
                    ]);
                }
            });

            // Keep the authenticated model in sync for the next request in this
            // session as well as for any listeners executed after verification.
            $member->refresh();

            // 8. Redirect to success
            return redirect()
                ->route('membership.success')
                ->with('success', 'Payment successful! Membership activated.')
                ->with('plan_id', $plan->id);
        } catch (\Exception $e) {

            \Log::error('Razorpay Payment Verification Failed', [
                'order_id' => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'member_id' => auth()->guard('member')->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('membership.failed')
                ->with(
                    'error',
                    'We could not complete your payment. Please try again.'
                );
        }
    }
}
