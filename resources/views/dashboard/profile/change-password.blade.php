@extends('layouts.dashboard')

@section('title', 'Change Password - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/memberships.css') }}?v={{ filemtime(public_path('assets/css/memberships.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/change-password.css') }}?v={{ filemtime(public_path('assets/css/change-password.css')) }}" />
@endsection

@section('content')

<section class="mp-page change-password-page" aria-labelledby="password-heading">
    <header class="mp-heading">
        <span class="mp-eyebrow"><i data-lucide="shield-check" aria-hidden="true"></i> KEEP YOUR ACCOUNT SECURE</span>
        <h1 id="password-heading">Your journey, protected.<br><span>A fresh password helps.</span></h1>
        <p>Update your password to keep your profile and connections secure.</p>
    </header>

    <div class="mp-card password-card">

        <div class="mp-card-top"><span class="mp-small-icon"><i data-lucide="key-round" aria-hidden="true"></i></span><span class="mp-overline">ACCOUNT SECURITY</span></div>
        <div class="card-header-custom">
            <h2>Change password</h2>
            <p>Enter your current password, then choose a new one.</p>
        </div>

        <form id="changePasswordForm" action="{{ route('update-password') }}" method="POST">

            @csrf

            <div class="mb-4">

                <label class="form-label" for="current_password">
                    Current Password
                </label>

                <div class="password-input">

                    <input
                        type="password"
                        name="current_password" id="current_password" autocomplete="current-password" required
                        class="form-control"
                        placeholder="Enter current password">

                    <button type="button" class="toggle-password" aria-label="Show password" aria-pressed="false">
                        <i data-lucide="eye" width="20" height="20" aria-hidden="true"></i>
                    </button>

                </div>

                <small class="text-danger current_password_error"></small>

            </div>

            <div class="mb-4">

                <label class="form-label" for="new_password">
                    New Password
                </label>

                <div class="password-input">

                    <input
                        type="password"
                        name="new_password" id="new_password" autocomplete="new-password" required
                        class="form-control"
                        placeholder="Enter new password">

                    <button type="button" class="toggle-password" aria-label="Show password" aria-pressed="false">
                        <i data-lucide="eye" width="20" height="20" aria-hidden="true"></i>
                    </button>

                </div>

                <small class="text-danger new_password_error"></small>

            </div>

            <div class="mb-4">

                <label class="form-label" for="new_password_confirmation">
                    Confirm Password
                </label>

                <div class="password-input">

                    <input
                        type="password"
                        name="new_password_confirmation" id="new_password_confirmation" autocomplete="new-password" required
                        class="form-control"
                        placeholder="Confirm new password">

                    <button type="button" class="toggle-password" aria-label="Show password" aria-pressed="false">
                        <i data-lucide="eye" width="20" height="20" aria-hidden="true"></i>
                    </button>

                </div>

            </div>

            <div class="password-guidance"><i data-lucide="shield-check" aria-hidden="true"></i><p>Use at least 8 characters. Choose a unique password you don’t use for other accounts.</p></div>
            <div class="password-actions">
                <a href="{{ route('home') }}" class="mp-button mp-button--outline">Back to dashboard</a>
                <button type="submit" class="mp-button mp-button--primary update-btn">Update Password</button>
            </div>

        </form>

    </div>
    <div id="epSuccessToast" class="ep-toast" role="status" aria-live="polite">
        <span id="epToastMsg"></span>
    </div>

</section>

@endsection
@section('scripts')
<script src="{{ asset('assets/js/change-password.js') }}?v={{ filemtime(public_path('assets/js/change-password.js')) }}"></script>
@endsection