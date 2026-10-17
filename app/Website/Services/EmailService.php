<?php

namespace App\Website\Services;

use App\Website\Mail\RegisterEmail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendRegisterEmail($member, ?string $siteCode = null)
    {
        if (! filter_var($member->email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $siteKey = $siteCode ?? config('site.current.code');
        $code = null;
        foreach (config('site.sites', []) as $host => $site) {
            if ($siteKey === $host || $siteKey === $site['code']) {
                $code = $site['code'];
                break;
            }
        }
        $settings = config('account_mail.sites.'.$code);
        if (! $code || ! $settings || ! $settings['enabled']) {
            return;
        }
        $smtp = config('mail.mailers.'.$settings['mailer']);
        if (! $settings['address'] || ! $settings['url']
            || empty($smtp['host']) || empty($smtp['username']) || empty($smtp['password'])) {
            return;
        }

        try {
            Mail::mailer($settings['mailer'])->to($member->email)->send(new RegisterEmail([
                'memberName' => $member->full_name ?: 'Member',
                'profileId' => $member->profile_id,
                'profileUrl' => rtrim($settings['url'], '/').'/edit-profile',
            ], $settings));
        } catch (\Throwable $exception) {
            // A mail outage must not undo a successfully created account.
            report($exception);
        }
    }

    public function interestEmail($member)
    {
        if ($member['status'] == 0) {
            $message = 'Dear user, You have got interest from a new '.$member['from_profile_id'].',Please check your account: '.config('site.current.display_name', config('app.name'));
        } elseif ($member['status'] == 1) {
            $message = 'Dear user, Your interest has been accepted by '.$member['from_profile_id'].' Please check your account: '.config('site.current.display_name', config('app.name'));
        } elseif ($member['status'] == 2) {
            $message = 'Dear user, Your interest has been rejected by '.$member['from_profile_id'].' Please check your account: '.config('site.current.display_name', config('app.name'));
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
        $message = ''.$user->profile_id.' Viewed Your Profile, Please check your account: '.config('site.current.display_name', config('app.name'));
        $details = [
            'name' => $member->full_name,
            'message' => $message,
        ];

        // Mail::to($member->email)->send(new RegisterEmail($details));
    }

    public function shortlist($member, $user)
    {
        $message = ''.$user->profile_id.' Shortlist Your Profile, Please check your account: '.config('site.current.display_name', config('app.name'));
        $details = [
            'name' => $member->full_name,
            'message' => $message,
        ];

        // Mail::to($member->email)->send(new RegisterEmail($details));
    }
}
