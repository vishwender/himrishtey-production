<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\MemberWallet;
use App\Website\Models\WalletOffer;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class WalletController extends Controller
{
    public function index()
    {
        $memberId = Auth::guard('member')->id();

        $transactions = MemberWallet::where('member_id', $memberId)
            ->latest()
            ->get();

        $balance = MemberWallet::where('member_id', $memberId)
            ->latest()
            ->value('wallet_balance') ?? 0;

        $offers = WalletOffer::all();

        return view('dashboard.wallet.index', compact('balance', 'transactions', 'offers'));
    }

    public function createOrder(Request $request)
    {

        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $api = new Api(config('website.runtime.razorpay_key'), config('website.runtime.razorpay_secret'));

        $order = $api->order->create([
            'receipt' => 'wallet_'.uniqid(),
            'amount' => $request->amount * 100,
            'currency' => 'INR',
        ]);
        session([
            'razorpay_order_id' => $order['id'],
            'order_amount' => $request->amount,
        ]);

        return response()->json([
            'success' => true,
            'order_id' => $order['id'],
            'razor_key' => config('website.runtime.razorpay_key'),
            'amount' => $request->amount * 100,
            'currency' => 'INR',
        ]);
    }

    public function buyOffer(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|integer',
        ]);

        $user = auth()->guard('member')->user();

        /*
        |--------------------------------------------------------------------------
        | Find Offer
        |--------------------------------------------------------------------------
        */

        $offer = WalletOffer::findOrFail(
            $request->offer_id
        );

        /*
        |--------------------------------------------------------------------------
        | Get Price From Database
        |--------------------------------------------------------------------------
        */

        $amount = (float) $offer->amount;

        /*
        |--------------------------------------------------------------------------
        | Create Razorpay Order
        |--------------------------------------------------------------------------
        */

        $api = new Api(config('website.runtime.razorpay_key'), config('website.runtime.razorpay_secret'));

        $orderData = [

            'receipt' => 'offer_'.
                $user->id.
                '_'.
                time(),

            'amount' => (int) round($amount * 100),

            'currency' => 'INR',

            'notes' => [

                'member_id' => $user->id,

                'offer_id' => $offer->id,

                'purpose' => 'wallet_offer',

            ],

            'payment_capture' => 1,

        ];

        $order = $api
            ->order
            ->create($orderData);

        /*
        |--------------------------------------------------------------------------
        | Save Pending Payment
        |--------------------------------------------------------------------------
        */

        DB::connection('site')->table('member_wallet_payments')->insert([

            'member_id' => $user->id,
            'amount' => $amount,
            'payment_id' => $order['id'],

        ]);

        return response()->json([

            'success' => true,

            'order_id' => $order['id'],

            'amount' => $order['amount'],

            'currency' => $order['currency'],

            'key' => config('website.runtime.razorpay_key'),

            'description' => $offer->title,

        ]);
    }

    public function paymentCallback(Request $request)
    {
        // dd($request);
        $api = new Api(config('website.runtime.razorpay_key'), config('website.runtime.razorpay_secret'));
        $userId = Auth::guard('member')->id();

        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature($attributes);
            $amount = session('order_amount');

            DB::connection('site')->table('payments')->insert([
                'member_id' => $userId,
                'payment_date' => now(),
                'payment_id' => $request->razorpay_payment_id,
                'amount' => $amount,
                'remarks' => 'Razorpay',
            ]);

            $lastWallet = MemberWallet::where('member_id', $userId)
                ->latest('id')
                ->first();

            $currentBalance = $lastWallet ? $lastWallet->wallet_balance : 0;

            $newBalance = $currentBalance + $amount;

            $wallet = MemberWallet::create([
                'member_id' => $userId,
                'amount_added' => $amount,
                'amount_deducted' => 0,
                'wallet_balance' => $newBalance,

            ]);

            session(['wallet_balance' => $wallet->wallet_balance]);

            return response()->json([
                'status' => 'success',
                'message' => 'Wallet recharged successfully!',
                'balance' => $wallet->wallet_balance,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment failed: '.$e->getMessage(),
            ], 500);
        }
    }
}
