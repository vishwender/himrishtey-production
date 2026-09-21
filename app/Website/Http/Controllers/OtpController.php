<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\Member;
use App\Website\Models\User;
use App\Website\Services\NimbusSmsService;
use Auth;
use Illuminate\Http\Request;
// your Member model
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Profiler\Profile;

class OtpController extends Controller
{
    public function sendOtp(Request $request, NimbusSmsService $smsService)
    {
        $member = Member::where('mobile_number', $request->phone)->first();

        if (! $member) {
            return response()->json(['message' => 'Mobile number not registered'], 404);
        }

        $otp = rand(1000, 9999);

        Session::put('otp', $otp);
        Session::put('otp_mobile', $request->phone);
        Session::put('otp_expires', now()->addMinutes(5));
        $smsService->sendOtp($request->phone, $otp);

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        if (now()->greaterThan(Session::get('otp_expires'))) {
            return response()->json(['message' => 'OTP expired'], 422);
        }

        if (Session::get('otp') == $request->otp) {
            return response()->json([
                'message' => 'Mobile number verified successfully.',
                'redirect' => '/home',
            ]);
        }

        return response()->json(['message' => 'Invalid OTP'], 422);
    }

    public function update_password(Request $request)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $opt_mobile = Session::get('otp_mobile');
        $member = Member::where('mobile_number', $opt_mobile)->first();
        if (! empty($member)) {
            $member->password = $request->password;
            $member->update();

            return response()->json(['message' => 'Password updated. Please login']);
        }
    }

    public function login_otp(
        Request $request,
        NimbusSmsService $messageService
    ) {
        $request->validate([
            'login' => 'required|string',
        ]);

        $login = trim($request->login);

        /*
    |--------------------------------------------------------------------------
    | Find member by mobile / email / profile ID
    |--------------------------------------------------------------------------
    */

        $member = Member::where('mobile_number', $login)
            ->orWhere('email', $login)
            ->orWhere('profile_id', $login)
            ->first();

        if (! $member) {

            return response()->json([
                'status' => 'error',
                'message' => 'No account found with the provided Profile ID, Email or Mobile Number.',
            ], 404);
        }

        /*
    |--------------------------------------------------------------------------
    | Check mobile number
    |--------------------------------------------------------------------------
    */

        if (empty($member->mobile_number)) {

            return response()->json([
                'status' => 'error',
                'message' => 'No mobile number is registered with this account.',
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | Generate OTP
    |--------------------------------------------------------------------------
    */

        $otp = random_int(1000, 9999);

        /*
    |--------------------------------------------------------------------------
    | Store OTP
    |--------------------------------------------------------------------------
    */

        session([
            'login_otp' => $otp,
            'otp_mobile' => $member->mobile_number,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        /*
    |--------------------------------------------------------------------------
    | Send SMS
    |--------------------------------------------------------------------------
    */

        $messageService->send_login_otp(
            $member->mobile_number,
            $otp
        );

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully.',
        ]);
    }

    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if (now()->greaterThan(session('otp_expires_at'))) {
            return response()->json(['status' => 'error', 'message' => 'OTP expired.']);
        }

        if (session('login_otp') == $request->otp) {
            $member = Member::where('mobile_number', session('otp_mobile'))->first();
            if ($member) {
                auth('member')->login($member);

                return response()->json(['status' => 'success', 'message' => 'Login successful.', 'member' => $member]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid OTP.', 'data' => session('login_otp'), 'otp' => $request->otp]);
        }
    }

    public function callbackRequest(Request $request, NimbusSmsService $smsService)
    {
        /*
    |--------------------------------------------------------------------------
    | Check member login
    |--------------------------------------------------------------------------
    */
        $loggedInUser = Auth::guard('member')->user();

        if (! $loggedInUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login first.',
            ], 401);
        }

        /*
    |--------------------------------------------------------------------------
    | Get logged-in user's name
    |--------------------------------------------------------------------------
    */

        $profileId = $loggedInUser->profile_id;

        /*
    |--------------------------------------------------------------------------
    | Get Callback Request user
    |--------------------------------------------------------------------------
    */

        $callbackUser = User::where('display_name', 'Call Back Request')->first();

        if (! $callbackUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Call Back Request user not found.',
            ], 404);
        }

        /*
    |--------------------------------------------------------------------------
    | Get display name
    |--------------------------------------------------------------------------
    */

        $username = $callbackUser->username;
        $phone = $callbackUser->phone;

        /*
    |--------------------------------------------------------------------------
    | Send callback SMS
    |--------------------------------------------------------------------------
    */

        $result = $smsService->callback_request_msg($profileId, $username, $phone);

        /*
    |--------------------------------------------------------------------------
    | Check service result
    |--------------------------------------------------------------------------
    */

        if ($result['status'] !== 'success') {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
            ], 422);
        }
        /*
        |--------------------------------------------------------------------------
        | Start 10 minute cooldown
        |--------------------------------------------------------------------------
        */

        $expiresAt = now()->addMinutes(10);

        session()->put(
            'callback_expires_at',
            $expiresAt
        );

        /*
        |--------------------------------------------------------------------------
        | Success response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'status' => 'success',
            'message' => 'Your callback request has been sent successfully.',
            'remaining' => 600,
        ]);
    }

    public function callbackStatus()
    {
        $loggedInUser = Auth::guard('member')->user();

        if (! $loggedInUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login first.',
            ], 401);
        }

        if (! session()->has('callback_expires_at')) {
            return response()->json([
                'status' => 'ready',
                'remaining' => 0,
            ]);
        }

        $expiresAt = session('callback_expires_at');

        if (now()->greaterThanOrEqualTo($expiresAt)) {

            session()->forget('callback_expires_at');

            return response()->json([
                'status' => 'ready',
                'remaining' => 0,
            ]);
        }

        $remaining = now()->diffInSeconds($expiresAt);

        return response()->json([
            'status' => 'cooldown',
            'remaining' => $remaining,
        ]);
    }

    public function verify_account_request(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Check OTP expiry
    |--------------------------------------------------------------------------
    */

        if (now()->greaterThan(Session::get('otp_expires'))) {
            return response()->json([
                'message' => 'OTP expired',
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | Check OTP
    |--------------------------------------------------------------------------
    */

        if (Session::get('otp') == $request->otp) {

            /*
        |--------------------------------------------------------------------------
        | Get member
        |--------------------------------------------------------------------------
        */

            $phone = Session::get('verify_phone');

            $member = Member::where('mobile_number', $phone)->first();

            if (! $member) {
                return response()->json([
                    'message' => 'Member account not found.',
                ], 404);
            }

            /*
        |--------------------------------------------------------------------------
        | Update member
        |--------------------------------------------------------------------------
        */

            $member->member_type = 'Verified';

            $member->save();

            /*
        |--------------------------------------------------------------------------
        | Remove OTP session
        |--------------------------------------------------------------------------
        */

            Session::forget('otp');
            Session::forget('otp_expires');
            Session::forget('verify_phone');

            return response()->json([
                'message' => 'Account verified successfully.',
                'redirect' => '/home',
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Invalid OTP
    |--------------------------------------------------------------------------
    */

        return response()->json([
            'message' => 'Invalid OTP',
        ], 422);
    }
}
