<?php

namespace App\Website\Services;

use App\Website\Mail\ContactConfirmation;
use Illuminate\Support\Facades\Mail;

class ContactConfirmationService
{
    public function send($inquiry): void
    {
        // Resolve from the saved inquiry, never from a previous request's site.
        $siteKey = $inquiry->site_key ?? null;
        $code = null;
        foreach (config('site.sites', []) as $host => $site) {
            if ($siteKey === $host || $siteKey === $site['code']) {
                $code = $site['code'];
                break;
            }
        }
        $settings = config('contact_mail.sites.'.$code);
        if (! $code || ! $settings || ! $settings['enabled']) {
            return;
        }
        $smtp = config('mail.mailers.'.$settings['mailer']);
        if (! $settings['address'] || ! $settings['url']
            || empty($smtp['host']) || empty($smtp['username']) || empty($smtp['password'])) {
            return;
        }

        try {
            Mail::mailer($settings['mailer'])->to($inquiry->email)->send(new ContactConfirmation(
                $inquiry->name, $inquiry->subject, $inquiry->message, $settings
            ));
        } catch (\Throwable $exception) {
            // Preserve the saved inquiry and successful submission during mail outages.
            report($exception);
        }
    }
}
