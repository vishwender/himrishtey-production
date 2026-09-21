<?php

namespace App\Website\Http\Controllers\Auth;

use App\Website\Http\Controllers\Controller;
use App\Website\Models\Member;
use App\Website\Services\NimbusSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    protected $smsService;

    public function __construct(NimbusSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show forgot password page
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => [
                'required',
                'digits:10',
                'regex:/^[6-9][0-9]{9}$/',
            ],
        ], [
            'mobile_number.required' => 'Please enter your mobile number.',
            'mobile_number.digits' => 'Mobile number must be 10 digits.',
            'mobile_number.regex' => 'Please enter a valid mobile number.',
        ]);

        $mobile = $request->mobile_number;

        /*
        |--------------------------------------------------------------------------
        | Check member
        |--------------------------------------------------------------------------
        */

        $member = Member::where(
            'mobile_number',
            $mobile
        )->first();

        if (! $member) {

            return response()->json([
                'message' => 'No account found with this mobile number.',
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
        | Store OTP in session
        |--------------------------------------------------------------------------
        */

        Session::put('forgot_password_mobile', $mobile);

        Session::put('forgot_password_otp', $otp);

        Session::put(
            'forgot_password_otp_expires',
            now()->addMinutes(5)
        );

        Session::put(
            'forgot_password_otp_verified',
            false
        );

        $smsResponse =
            $this->smsService->sendOtp(
                $mobile,
                $otp
            );
        // dd($smsResponse);

        if (! $smsResponse['success']) {

            return response()->json([
                'message' => 'Unable to send OTP. Please try again.',
            ], 500);
        }

        return response()->json([

            'success' => true,

            'message' => 'OTP sent successfully.',

        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        $sessionOtp =
            Session::get('forgot_password_otp');

        $expires =
            Session::get('forgot_password_otp_expires');

        /*
        |--------------------------------------------------------------------------
        | Check OTP exists
        |--------------------------------------------------------------------------
        */

        if (! $sessionOtp || ! $expires) {

            return response()->json([
                'message' => 'OTP not found. Please request a new OTP.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check expiry
        |--------------------------------------------------------------------------
        */

        if (now()->greaterThan($expires)) {

            Session::forget([
                'forgot_password_otp',
                'forgot_password_otp_expires',
            ]);

            return response()->json([
                'message' => 'OTP has expired. Please request a new OTP.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify OTP
        |--------------------------------------------------------------------------
        */

        if ((string) $sessionOtp !== (string) $request->otp) {

            return response()->json([
                'message' => 'Invalid OTP.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | OTP verified
        |--------------------------------------------------------------------------
        */

        Session::put(
            'forgot_password_otp_verified',
            true
        );

        Session::forget('forgot_password_otp');

        return response()->json([

            'success' => true,

            'message' => 'OTP verified successfully.',

        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ], [

            'password.required' => 'Please enter a new password.',

            'password.min' => 'Password must be at least 8 characters.',

            'password.confirmed' => 'Passwords do not match.',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Make sure OTP was verified
        |--------------------------------------------------------------------------
        */

        if (
            Session::get(
                'forgot_password_otp_verified'
            ) !== true
        ) {

            return response()->json([
                'message' => 'Please verify the OTP first.',
            ], 403);
        }

        $mobile =
            Session::get('forgot_password_mobile');

        if (! $mobile) {

            return response()->json([
                'message' => 'Password reset session expired.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Find member
        |--------------------------------------------------------------------------
        */

        $member = Member::where(
            'mobile_number',
            $mobile
        )->first();

        if (! $member) {

            return response()->json([
                'message' => 'Account not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Update password
        |--------------------------------------------------------------------------
        */

        $member->password = $request->password;

        $member->save();

        /*
        |--------------------------------------------------------------------------
        | Clear reset session
        |--------------------------------------------------------------------------
        */

        Session::forget([
            'forgot_password_mobile',
            'forgot_password_otp',
            'forgot_password_otp_expires',
            'forgot_password_otp_verified',
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Password reset successfully.',

            'redirect' => route('login-form'),

        ]);
    }
}
