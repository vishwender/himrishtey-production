@extends('layouts.dashboard')

@section('title', 'Change Password')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/css/change-password.css')}}" />
@endsection

@section('content')

<main class="change-password-page">

    <div class="password-card">

        <div class="card-header-custom">
            <h2>Change Password</h2>
            <p>Update your account password to keep your account secure.</p>
        </div>

        <form id="changePasswordForm">

            @csrf

            <div class="mb-4">

                <label class="form-label">
                    Current Password
                </label>

                <div class="password-input">

                    <input
                        type="password"
                        name="current_password"
                        class="form-control"
                        placeholder="Enter current password">

                    <span class="toggle-password">
                        <i class="bi bi-eye"></i>
                    </span>

                </div>

                <small class="text-danger current_password_error"></small>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    New Password
                </label>

                <div class="password-input">

                    <input
                        type="password"
                        name="new_password"
                        class="form-control"
                        placeholder="Enter new password">

                    <span class="toggle-password">
                        <i class="bi bi-eye"></i>
                    </span>

                </div>

                <small class="text-danger new_password_error"></small>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Confirm Password
                </label>

                <div class="password-input">

                    <input
                        type="password"
                        name="new_password_confirmation"
                        class="form-control"
                        placeholder="Confirm new password">

                    <span class="toggle-password">
                        <i class="bi bi-eye"></i>
                    </span>

                </div>

            </div>

            <button type="submit" class="btn update-btn">
                Update Password
            </button>

        </form>

    </div>
    <div id="epSuccessToast" class="ep-toast">
        <span id="epToastMsg"></span>
    </div>

</main>

@endsection
@section('scripts')
<script src="{{ asset('assets/js/change-password.js') }}"></script>
@endsection