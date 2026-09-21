<?php

namespace App\Website\Services;

use App\Website\Mail\RegisterEmail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendRegisterEmail($member)
    {
        // test email.remove on live
        $email = 'vishwender.baloria@gmail.com';
        $details = [
            'name' => $member->full_name,

            'message' => 'Thanks for register with HimRishtey. Please complete your registeration and find out perfect match for you',
        ];

        // Mail::to($email)->send(new RegisterEmail($details));
        // Mail::to($member->email)->send(new RegisterEmail($details));
    }

    public function interestEmail($member)
    {
        if ($member['status'] == 0) {
            $message = 'Dear user, You have got interest from a new '.$member['from_profile_id'].',Please check your account : HIMRMB';
        } elseif ($member['status'] == 1) {
            $message = 'Dear user, Your interest has been accepted by '.$member['from_profile_id'].' Please check your account : HIMRMB';
        } elseif ($member['status'] == 2) {
            $message = 'Dear user, Your interest has been rejected by '.$member['from_profile_id'].' Please check your account : HIMRMB';
        } else {
            $message = '';
        }
        $details = [
            'name' => $member['to_full_name'],
            'message' => $message,
        ];

        // Mail::to($member['to_email'])->send(new RegisterEmail($details));
    }

    public function viewProfile($member, $user)
    {
        $message = ''.$user->profile_id.' Viewed Your Profile, Please check your account : HIMRMB';
        $details = [
            'name' => $member->full_name,
            'message' => $message,
        ];

        // Mail::to($member->email)->send(new RegisterEmail($details));
    }

    public function shortlist($member, $user)
    {
        $message = ''.$user->profile_id.' Shortlist Your Profile, Please check your account : HIMRMB';
        $details = [
            'name' => $member->full_name,
            'message' => $message,
        ];

        // Mail::to($member->email)->send(new RegisterEmail($details));
    }
}
