@extends('layouts.public')

@section('title', 'Frequently Asked Questions - ' . $siteName)
@section('description', 'Find answers to common questions about profiles, privacy, memberships and matrimonial services on ' . $siteName . '.')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/faqs.css') }}">
@endsection

@section('content')
<div class="faq-page">
    <section class="faq-hero" aria-labelledby="faq-title">
        <span class="faq-kicker">Help Center</span>
        <h1 id="faq-title">Frequently asked questions</h1>
        <p>Everything you need to know to begin your matrimonial journey with confidence.</p>
    </section>

    <section class="faq-shell" aria-label="Frequently asked questions">
        @php
        $faqs = [
        ['How do I create a profile on ' . $siteName . '?', 'Select “Register Free”, enter your basic details and verify your mobile number. You can then complete your personal, family, education and partner-preference information from your profile.'],
        ['Is registration free?', 'Yes. Creating an account and completing your profile is free. Some features, such as viewing contact details or accessing premium services, may require a membership or wallet balance.'],
        ['How can I make my profile more trustworthy?', 'Add accurate information, upload a clear and recent photograph, complete every profile section and request account verification. A complete profile helps other families make informed decisions.'],
        ['Who can see my contact details?', 'Your contact details are not displayed openly to every visitor. Eligible logged-in members may access them according to the membership and contact-unlock rules available on the platform.'],
        ['How do I search for a suitable match?', 'After signing in, use Quick Search or Advanced Search. You can filter profiles by age, location, religion, community, education, occupation and other preferences.'],
        ['What happens when I send an interest?', 'The member receives your interest and can accept or decline it. You can review the status of sent and received interests from your Interest Box.'],
        ['Can I update my details after registration?', 'Yes. Open Edit Profile from your dashboard to update your personal details, family information, career, lifestyle and partner preferences.'],
        ['How do memberships and wallet credits work?', 'Membership plans provide platform benefits for a defined period. Wallet credits can be used for eligible paid actions, such as unlocking contact details, where applicable.'],
        ['How do I report a concern or request help?', 'Use the Contact Us page to reach the ' . $siteName . ' support team. Include your profile ID and a short description of the issue so the team can assist you efficiently.'],
        ['Can I request deletion of my profile?', 'Yes. Sign in, open Delete Profile and submit a reason. The request will be sent to the administrator for review and processing.'],
        ];
        @endphp

        <div class="faq-heading">
            <div>
                <span class="faq-count">{{ count($faqs) }} helpful answers</span>
                <h2>Common questions</h2>
            </div>
            <i data-lucide="messages-square" width="30" height="30" aria-hidden="true"></i>
        </div>

        <div class="faq-list">
            @foreach ($faqs as $index => [$question, $answer])
            <details class="faq-item" @if ($index===0) open @endif>
                <summary>
                    <span>{{ $question }}</span>
                    <span class="faq-toggle" aria-hidden="true"><i data-lucide="plus" width="19" height="19"></i></span>
                </summary>
                <div class="faq-answer">
                    <p>{{ $answer }}</p>
                </div>
            </details>
            @endforeach
        </div>
    </section>

    <section class="faq-contact" aria-label="Contact support">
        <div class="faq-contact-icon"><i data-lucide="heart-handshake" width="25" height="25" aria-hidden="true"></i></div>
        <div>
            <h2>Still have a question?</h2>
            <p>Our support team will be happy to help you.</p>
        </div>
        <a class="public-cta public-cta-primary" href="{{ route('contact-us') }}">Contact Us <i data-lucide="arrow-right" width="17" height="17" aria-hidden="true"></i></a>
    </section>
</div>
@endsection