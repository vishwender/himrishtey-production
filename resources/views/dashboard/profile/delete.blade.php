@extends('layouts.dashboard')

@section('title', 'Delete Profile - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/memberships.css') }}?v={{ filemtime(public_path('assets/css/memberships.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/delete-profile.css') }}?v={{ filemtime(public_path('assets/css/delete-profile.css')) }}">
@endsection

@section('content')
<section class="mp-page delete-profile-page" aria-labelledby="delete-profile-heading">
    <header class="mp-heading">
        <span class="mp-eyebrow"><i data-lucide="heart-handshake" aria-hidden="true"></i> YOUR JOURNEY, YOUR CHOICE</span>
        <h1 id="delete-profile-heading">Ready to leave?<br><span>We’re here to help.</span></h1>
        <p>Request to delete your profile and let us know why you’re leaving.</p>
    </header>

    <div class="mp-section-heading">
        <div><span class="mp-overline">YOUR PROFILE</span><h2>Delete profile</h2></div>
        <span class="mp-detail"><i data-lucide="shield-check" aria-hidden="true"></i> Reviewed by our team</span>
    </div>

    <div class="dp-grid">
        <form id="deleteProfileForm" class="mp-card dp-form">
            @csrf
            <div class="mp-card-top"><span class="mp-small-icon"><i data-lucide="message-square-heart" aria-hidden="true"></i></span><span class="mp-overline">HELP US UNDERSTAND</span></div>
            <fieldset class="dp-reasons">
                <legend>Why are you leaving?</legend>
                <p class="dp-description">Choose the reason that best describes your decision.</p>
                <div class="reason-list">
                    @foreach(['Getting Married', 'Found match on ' . $siteName, 'Found my match elsewhere', 'Unsatisfactory experience', 'Other'] as $reason)
                    <label class="reason-item">
                        <input type="radio" name="reason" value="{{ $reason }}" @checked($loop->first)>
                        <span class="custom-radio" aria-hidden="true"></span>
                        <span>{{ $reason }}</span>
                    </label>
                    @endforeach
                </div>
            </fieldset>
            <div id="otherReasonWrapper" class="other-reason" style="display:none;">
                <label for="otherReason">Tell us a little more</label>
                <textarea id="otherReason" name="other_reason" rows="4" placeholder="Please tell us why you’re leaving…"></textarea>
            </div>
            <div class="dp-actions">
                <a href="{{ route('home') }}" class="mp-button mp-button--outline">Keep my profile</a>
                <button type="submit" class="mp-button mp-button--primary delete-btn"><i data-lucide="user-x" aria-hidden="true"></i> Request deletion</button>
            </div>
        </form>

        <aside class="mp-card dp-information" aria-labelledby="delete-information-heading">
            <div class="mp-card-top"><span class="mp-small-icon"><i data-lucide="info" aria-hidden="true"></i></span><span class="mp-overline">BEFORE YOU GO</span></div>
            <h2 id="delete-information-heading">What happens next?</h2>
            <p>Your deletion request will be sent to our team for review.</p>
            <ul class="dp-notes">
                <li><i data-lucide="clipboard-check" aria-hidden="true"></i><div><strong>A request, then a review</strong><span>Submitting this form sends your request to the administrator.</span></div></li>
                <li><i data-lucide="user-x" aria-hidden="true"></i><div><strong>Permanent profile removal</strong><span>Once your profile is deleted, your matches, connections and chat history will be removed.</span></div></li>
            </ul>
            <div class="dp-stay"><span class="mp-overline">STILL EXPLORING?</span><p>You can keep your profile and continue your search at your own pace.</p><a href="{{ route('memberships') }}" class="mp-category-link">Explore memberships <i data-lucide="arrow-up-right" aria-hidden="true"></i></a></div>
        </aside>
    </div>
</section>
<div id="epSuccessToast" class="ep-toast" role="status" aria-live="polite"><span id="epToastMsg"></span></div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/delete-profile.js') }}?v={{ filemtime(public_path('assets/js/delete-profile.js')) }}"></script>
@endsection
