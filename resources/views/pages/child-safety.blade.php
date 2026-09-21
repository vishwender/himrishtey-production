@extends('layouts.public')

@section('title', 'Child Safety Standard - ' . $siteName)
@section('description', $siteName . ' is an adults-only matrimonial service. Learn how to report child-safety concerns and prohibited activity.')


@section('content')
<main class="legal-page" id="main-content">
    <section class="legal-hero" aria-labelledby="childSafetyTitle">
        <p class="legal-kicker"><i data-lucide="shield-check" width="14" height="14"></i> Trust &amp; Safety</p>
        <h1 id="childSafetyTitle">Child Safety Standard</h1>
        <p>{{ $siteName }} is an adults-only matrimonial service. Protecting minors and responding quickly to safety concerns are fundamental requirements of our platform.</p>
    </section>
    <article class="legal-card" aria-label="Child safety standard">
        <header class="legal-card-header">
            <div class="legal-card-icon" aria-hidden="true"><i data-lucide="shield-alert" width="23" height="23"></i></div>
            <h2>Our commitment to child safety</h2>
        </header>
        <div class="legal-content">
            <section class="legal-safety-section">
                <h3><i data-lucide="user-round-check" width="20" height="20"></i> Adults only</h3>
                <p>Users must be legally eligible adults seeking matrimonial connections. We do not permit accounts created by or for minors, or any attempt to contact a minor through the platform.</p>
            </section>
            <section class="legal-safety-section">
                <h3><i data-lucide="ban" width="20" height="20"></i> Prohibited activity</h3>
                <p>Child sexual abuse or exploitation, grooming, sexualized content involving minors, impersonation of a minor, and attempts to move such conversations off-platform are strictly prohibited.</p>
            </section>
            <section class="legal-safety-section">
                <h3><i data-lucide="flag" width="20" height="20"></i> Report a concern</h3>
                <p>If you encounter a profile, message, image, or other behavior that may involve a minor, stop engaging and report it immediately. Include relevant profile IDs and details, but do not download, copy, or redistribute potentially illegal material.</p>
            </section>
            <section class="legal-safety-section">
                <h3><i data-lucide="search-check" width="20" height="20"></i> How we respond</h3>
                <p>We review reports promptly and may restrict or remove accounts, preserve relevant records, and cooperate with appropriate authorities where required by law. If a child is in immediate danger, contact local emergency services first.</p>
            </section>
        </div>
    </article>
    <section class="legal-support"><i data-lucide="shield-alert" width="22" height="22"></i>
        <p>Report a child-safety concern to our support team immediately.</p><a class="public-cta public-cta-primary" href="{{ route('contact-us') }}">Report a Concern</a>
    </section>
</main>
@endsection