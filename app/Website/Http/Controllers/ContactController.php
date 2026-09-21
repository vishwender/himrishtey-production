<?php

namespace App\Website\Http\Controllers;

use App\Website\Http\Requests\ContactRequest;
use App\Website\Mail\ContactInquiry;
use App\Website\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(Request $request): View
    {
        $first = random_int(1, 9);
        $second = random_int(1, 9);
        $request->session()->put('contact_captcha', [
            'answer' => $first + $second,
            'expires_at' => now()->addMinutes(30)->timestamp,
        ]);

        return view('pages.contact', ['captchaQuestion' => "$first + $second"]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        unset($validated['website'], $validated['captcha']);
        $request->session()->forget('contact_captcha');

        $message = ContactMessage::create($validated + [
            'site_key' => config('site.current.key'),
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
        ]);

        $supportEmail = config('site.current.support_email') ?: config('mail.from.address');
        try {
            Mail::to($supportEmail)->send(new ContactInquiry($message));
        } catch (\Throwable $exception) {
            // The inquiry is already saved; a mail outage must not lose it or
            // prompt the visitor to submit a duplicate.
            report($exception);
        }

        return redirect()->route('contact-us')->with(
            'contact_success',
            'Thank you! Your message has been received. Our support team will contact you shortly.'
        );
    }
}
