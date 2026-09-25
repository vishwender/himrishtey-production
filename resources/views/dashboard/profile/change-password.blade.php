@extends('layouts.dashboard')

@section('title', 'Change Password')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/change-password.css') }}?v={{ filemtime(public_path('assets/css/change-password.css')) }}" />
@endsection

@section('content')

<main class="change-password-page">

    <div class="password-card">

        <div class="card-header-custom">
            <h2>Change Password</h2>
            <p>Update your account password to keep your account secure.</p>
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

            <button type="submit" class="btn update-btn">
                Update Password
            </button>

        </form>

    </div>
    <div id="epSuccessToast" class="ep-toast" role="status" aria-live="polite">
        <span id="epToastMsg"></span>
    </div>

</main>

@endsection
@section('scripts')
<script src="{{ asset('assets/js/change-password.js') }}?v={{ filemtime(public_path('assets/js/change-password.js')) }}"></script>
@endsection