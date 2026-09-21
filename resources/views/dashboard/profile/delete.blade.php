@extends('layouts.dashboard')

@section('title', 'Delete Profile - ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/delete-profile.css') }}?v={{ filemtime(public_path('assets/css/delete-profile.css')) }}">
@endsection

@section('content')

<main class="delete-profile-page">

    <div class="delete-wrapper">

        <div class="delete-header">
            <h1>Delete Profile</h1>

            <p>
                After pressing the delete button your profile will be permanently deleted.
                All matches you've looked, connected or contacted and chat history
                will be deleted.
            </p>
        </div>

        <form id="deleteProfileForm">

            @csrf

            <div class="reason-list">

                <label class="reason-item">
                    <input type="radio" name="reason" value="Getting Married" checked>
                    <span class="custom-radio"></span>
                    <span>Getting Married</span>
                </label>

                <label class="reason-item">
                    <input type="radio" name="reason" value="Found match on {{ $siteName }}">
                    <span class="custom-radio"></span>
                    <span>Found match on {{ $siteName }}</span>
                </label>

                <label class="reason-item">
                    <input type="radio" name="reason" value="Found my match elsewhere">
                    <span class="custom-radio"></span>
                    <span>Found my match elsewhere</span>
                </label>

                <label class="reason-item">
                    <input type="radio" name="reason" value="Unsatisfactory experience">
                    <span class="custom-radio"></span>
                    <span>Unsatisfactory experience</span>
                </label>

                <label class="reason-item">
                    <input type="radio" name="reason" value="Other">
                    <span class="custom-radio"></span>
                    <span>Other</span>
                </label>

            </div>

            <div id="otherReasonWrapper" class="other-reason" style="display:none;">
                <textarea
                    name="other_reason"
                    rows="4"
                    placeholder="Please tell us why you're leaving..."></textarea>
            </div>

            <button type="submit" class="delete-btn">
                Delete Profile
            </button>

        </form>

    </div>

</main>
<div id="epSuccessToast" class="ep-toast">
    <span id="epToastMsg"></span>
</div>

@endsection

@section('scripts')

<script src="{{ asset('assets/js/delete-profile.js') }}"></script>

@endsection
