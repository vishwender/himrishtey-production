@extends('layouts.public')

@section('title', 'Contact Us - ' . $siteName)
@section('description', 'Contact the ' . $siteName . ' support team for help with your profile, membership, account, or matrimonial journey.')

@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
@endpush

@section('content')
@php
$contactAddress = $siteKey === 'himrishtey.com'
? "1st Floor, Manali, Mandi - Pathankot Road\nOpp. Palam Hardware Kalu Ki Hatti\nBag Uparla, Palampur\nHimachal Pradesh 176102"
: $siteSupportAddress;
@endphp
<div class="contact-page" id="main-content">
    <section class="contact-hero" aria-labelledby="contact-title">
        <span class="contact-kicker">Contact Us</span>
        <h1 id="contact-title">How can we help?</h1>
        <p>Questions about your profile, membership, or matches? Send us a message and our team will get back to you.</p>
    </section>

    <div class="contact-layout">
        <aside class="contact-details" aria-label="Contact details">
            <div class="contact-details-heading">
                <span>Talk to our team</span>
                <h2>We’re here for your journey</h2>
                <p>For quicker assistance, include your profile ID when contacting us.</p>
            </div>
            <a class="contact-method" href="tel:{{ preg_replace('/\s+/', '', $siteSupportPhone ?: '+91 9459170004') }}">
                <span class="contact-method-icon"><i data-lucide="phone" width="21" height="21" aria-hidden="true"></i></span>
                <span><small>Call our helpline</small><strong>{{ $siteSupportPhone ?: '+91 9459170004' }}</strong></span>
            </a>
            <a class="contact-method" href="mailto:{{ $siteSupportEmail ?: 'himrishtey@gmail.com' }}">
                <span class="contact-method-icon"><i data-lucide="mail" width="21" height="21" aria-hidden="true"></i></span>
                <span><small>Email support</small><strong>{{ $siteSupportEmail ?: 'himrishtey@gmail.com' }}</strong></span>
            </a>
            @if ($contactAddress)
            <a class="contact-method contact-method-address" href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode(str_replace("\n", ', ', $contactAddress)) }}" target="_blank" rel="noopener noreferrer">
                <span class="contact-method-icon"><i data-lucide="map-pin" width="21" height="21" aria-hidden="true"></i></span>
                <span>
                    <small>Visit our office</small>
                    <strong>{!! nl2br(e($contactAddress)) !!}</strong>
                </span>
            </a>
            @endif
            <div class="contact-hours">
                <i data-lucide="clock-3" width="19" height="19" aria-hidden="true"></i>
                <div><strong>Support hours</strong><span>Monday–Saturday, 9:30 AM–6:00 PM</span></div>
            </div>
        </aside>

        <section class="contact-form-card" aria-labelledby="contact-form-title">
            <div class="contact-form-heading">
                <span class="contact-form-icon"><i data-lucide="send" width="20" height="20" aria-hidden="true"></i></span>
                <div>
                    <h2 id="contact-form-title">Send us a message</h2>
                    <p>Fields marked with * are required.</p>
                </div>
            </div>

            @if (session('contact_success'))
            <div class="contact-alert contact-alert-success" role="status"><i data-lucide="circle-check" width="20" height="20" aria-hidden="true"></i><span>{{ session('contact_success') }}</span></div>
            @endif
            @if ($errors->any())
            <div class="contact-alert contact-alert-error" role="alert"><i data-lucide="circle-alert" width="20" height="20" aria-hidden="true"></i><span>Please check the highlighted fields and try again.</span></div>
            @endif

            <form method="POST" action="{{ route('contact-us.submit') }}" class="contact-form">
                @csrf
                <div class="contact-honeypot" aria-hidden="true"><label for="contact-website">Website</label><input id="contact-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>

                <div class="contact-field-row">
                    <div class="contact-field">
                        <label for="contact-name">Your name <span>*</span></label>
                        <input id="contact-name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="100" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                        @error('name')<small class="contact-error" id="name-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="contact-field">
                        <label for="contact-email">Email address <span>*</span></label>
                        <input id="contact-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="190" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')<small class="contact-error" id="email-error">{{ $message }}</small>@enderror
                    </div>
                </div>

                <div class="contact-field-row">
                    <div class="contact-field">
                        <label for="contact-phone">Phone number</label>
                        <input id="contact-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" maxlength="30" placeholder="e.g. +91 98765 43210" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
                        @error('phone')<small class="contact-error" id="phone-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="contact-field">
                        <label for="contact-profile">Profile ID <small>(if registered)</small></label>
                        <input id="contact-profile" name="profile_id" type="text" value="{{ old('profile_id') }}" maxlength="50" placeholder="e.g. HIM12345" @error('profile_id') aria-invalid="true" aria-describedby="profile-error" @enderror>
                        @error('profile_id')<small class="contact-error" id="profile-error">{{ $message }}</small>@enderror
                    </div>
                </div>

                <div class="contact-field">
                    <label for="contact-subject">Subject <span>*</span></label>
                    <select id="contact-subject" name="subject" required @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror>
                        <option value="">Choose a topic</option>
                        @foreach (\App\Website\Models\ContactMessage::SUBJECTS as $subject)
                        <option value="{{ $subject }}" @selected(old('subject')===$subject)>{{ $subject }}</option>
                        @endforeach
                    </select>
                    @error('subject')<small class="contact-error" id="subject-error">{{ $message }}</small>@enderror
                </div>

                <div class="contact-field">
                    <label for="contact-message">How can we help? <span>*</span></label>
                    <textarea id="contact-message" name="message" rows="6" minlength="10" maxlength="3000" placeholder="Tell us a little about your question or concern…" required @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                    @error('message')<small class="contact-error" id="message-error">{{ $message }}</small>@enderror
                </div>

                <div class="contact-field">
                    <label for="contact-captcha">Security question: What is {{ $captchaQuestion }}? <span>*</span></label>
                    <input id="contact-captcha" name="captcha" type="text" inputmode="numeric" pattern="[0-9]+" autocomplete="off" required @error('captcha') aria-invalid="true" aria-describedby="captcha-error" @enderror>
                    @error('captcha')<small class="contact-error" id="captcha-error">{{ $message }}</small>@enderror
                </div>

                <div class="contact-form-footer">
                    <p><i data-lucide="shield-check" width="16" height="16" aria-hidden="true"></i> Your details are used only to respond to this inquiry.</p>
                    <button class="public-cta public-cta-primary" type="submit">Send Message <i data-lucide="arrow-right" width="17" height="17" aria-hidden="true"></i></button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
