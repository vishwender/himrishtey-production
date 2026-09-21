<?php

namespace App\Website\Services;

use Illuminate\Support\Facades\Http;

class NimbusSmsService
{
    public function sendOtp($mobile, $otp)
    {
        $messageText = ''.$otp.' is the OTP to reset your Password for your himrishtey account';
        $url = 'http://nimbusit.biz/api/SmsApi/SendMultipleApi';

        $response = Http::get($url, [
            'UserID' => config('website.runtime.nimbus_username'),
            'Password' => config('website.runtime.nimbus_password'),
            'SenderID' => config('website.runtime.nimbus_sender'),
            'Phno' => $mobile,
            'Msg' => $messageText,
            'EntityID' => config('website.runtime.nimbus_entity'),
            'TemplateID' => config('website.runtime.nimbus_registration_template'),
        ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'SMS API request failed.',
                'response' => $response->body(),
            ];
        }

        $data = $response->json();
        if (
            isset($data['Status']) &&
            $data['Status'] === 'OK'
        ) {
            return [
                'success' => true,
                'message' => 'SMS sent successfully.',
                'response' => $data,
            ];
        }

        return [
            'success' => false,
            'message' => 'SMS provider returned an error.',
            'response' => $data,
        ];

        return $response->body();
    }

    public function sendInterest($email)
    {
        $messageText = 'Dear user, You have got interest from a new '.$email['from_profile_id'].',Please check your account : HIMRMB';
        $url = 'http://nimbusit.biz/api/SmsApi/SendMultipleApi';

        $response = Http::get($url, [
            'UserID' => config('website.runtime.nimbus_username'),
            'Password' => config('website.runtime.nimbus_password'),
            'SenderID' => config('website.runtime.nimbus_sender'),
            'Phno' => $email['to_phone'],
            'Msg' => $messageText,
            'EntityID' => config('website.runtime.nimbus_entity'),
            'TemplateID' => config('website.runtime.nimbus_interest_template'),
        ]);

        return $response->body();
    }

    public function send_login_otp($mobile, $otp)
    {
        $messageText = ''.$otp.'  is the OTP to login your himrishtey account.';
        $url = 'http://nimbusit.biz/api/SmsApi/SendMultipleApi';

        $response = Http::get($url, [
            'UserID' => config('website.runtime.nimbus_username'),
            'Password' => config('website.runtime.nimbus_password'),
            'SenderID' => config('website.runtime.nimbus_sender'),
            'Phno' => $mobile,
            'Msg' => $messageText,
            'EntityID' => config('website.runtime.nimbus_entity'),
            'TemplateID' => config('website.runtime.nimbus_login_template'),
        ]);

        return $response->body();
    }

    public function callback_request_msg($profileId, $username, $phone)
    {
        $messageText = 'A call request from id '.$profileId.' regarding membership. Call back immediately '.$username.'.HIMRMB';
        $url = 'http://nimbusit.biz/api/SmsApi/SendMultipleApi';

        $response = Http::get($url, [
            'UserID' => config('website.runtime.nimbus_username'),
            'Password' => config('website.runtime.nimbus_password'),
            'SenderID' => config('website.runtime.nimbus_sender'),
            'Phno' => $phone,
            'Msg' => $messageText,
            'EntityID' => config('website.runtime.nimbus_entity'),
            'TemplateID' => config('website.runtime.nimbus_callback_template'),
        ]);
        if ($response->successful()) {
            return [
                'status' => 'success',
                'message' => 'SMS sent successfully.',
                'response' => $response->body(),
            ];
        }

        return [
            'status' => 'error',
            'message' => 'SMS API returned an error.',
            'response' => $response->body(),
        ];
    }

    public function verify_account($phone, $otp)
    {
        $messageText = $otp.' is the OTP to verify your mobile number for your himrishtey account';
        $url = 'http://nimbusit.biz/api/SmsApi/SendMultipleApi';

        $response = Http::get($url, [
            'UserID' => config('website.runtime.nimbus_username'),
            'Password' => config('website.runtime.nimbus_password'),
            'SenderID' => config('website.runtime.nimbus_sender'),
            'Phno' => $phone,
            'Msg' => $messageText,
            'EntityID' => config('website.runtime.nimbus_entity'),
            'TemplateID' => config('website.runtime.nimbus_verification_template'),
        ]);
        if ($response->successful()) {
            return [
                'status' => 'success',
                'message' => 'SMS sent successfully.',
                'response' => $response->body(),
            ];
        }

        return [
            'status' => 'error',
            'message' => 'SMS API returned an error.',
            'response' => $response->body(),
        ];
    }
}
