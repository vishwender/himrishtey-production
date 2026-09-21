@extends('layouts.dashboard')

@section('title', 'Dashboard - HimRishtey')

@section('content')

<!-- BREADCRUMB / PAGE TITLE -->
<div class="page-header-bar container-xxl">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb-custom">
            <li><i data-lucide="home" width="14" height="14"></i></li>
            <li aria-current="page">Home</li>
        </ol>
    </nav>
    <div class="page-header-actions">
        <button
            type="button"
            class="btn-primary-sm"
            id="openPhotoUpload">

            <i data-lucide="plus" width="15"></i>
            Add Photos

        </button>
        <a href="javascript:void(0);" id="openProfileSearch" class="btn-outline-sm">
            <i data-lucide="search" width="15" height="15"></i>
            Search Profiles
        </a>
    </div>
</div>

@if($member->member_type !== 'Verified')
<!-- VERIFY BANNER (conditional) -->
<div class="verify-banner container-xxl" id="verifyBanner">
    <div class="verify-banner-inner">
        <i data-lucide="shield-alert" width="20" height="20"></i>
        <span>Your profile is not verified. Verify now to get more matches and build trust.</span>
        <a href="{{route('verify-account')}}" class="verify-banner-btn">Verify Now</a>
    </div>
    <button class="verify-banner-dismiss" aria-label="Dismiss" onclick="document.getElementById('verifyBanner').remove()">
        <i data-lucide="x" width="16" height="16"></i>
    </button>
</div>
@endif

<!-- PROFILE COMPLETION -->
<section class="profile-completion-section container-xxl" aria-label="Profile completion">

    @if($completion < 100)

        {{-- ==========================================
             INCOMPLETE PROFILE
        =========================================== --}}
        <div class="pc-card">

        <div class="pc-left">

            <div class="pc-avatar-ring" style="--progress: {{ $completion }}%;">

                <img
                    src="{{ !empty($member?->photo) ? \App\Website\Services\ProfilePhotoUrl::get($member->photo) : asset('images/profile_photos/boy.jpg') }}"
                    alt="{{ $member->full_name }}"
                    class="pc-avatar">

                <svg class="pc-ring-svg" viewBox="0 0 80 80">

                    <circle
                        cx="40"
                        cy="40"
                        r="36"
                        fill="none"
                        stroke="var(--color-surface-offset)"
                        stroke-width="4" />

                    <circle
                        cx="40"
                        cy="40"
                        r="36"
                        fill="none"
                        stroke="var(--color-primary)"
                        stroke-width="4"
                        stroke-dasharray="226.2"
                        stroke-dashoffset="{{ $strokeOffset }}"
                        stroke-linecap="round"
                        transform="rotate(-90 40 40)" />

                </svg>

                <span class="pc-percent">
                    {{ $completion }}%
                </span>

            </div>

        </div>


        <div class="pc-right">

            <h3 class="pc-title">
                Complete Your Profile
            </h3>

            <p class="pc-desc">
                A complete profile gets <strong>5x more</strong> matches.
                Add your details to stand out.
            </p>


            {{-- Progress --}}
            <div class="pc-progress-bar-wrap">

                <div
                    class="pc-progress-bar"
                    style="width: {{ $completion }}%;"
                    role="progressbar"
                    aria-valuenow="{{ $completion }}"
                    aria-valuemin="0"
                    aria-valuemax="100"></div>

            </div>


            {{-- Completion percentage --}}
            <div class="pc-progress-info">
                <span>Profile completion</span>
                <strong>{{ $completion }}%</strong>
            </div>


            {{-- Steps --}}
            <div class="pc-steps">

                @foreach($steps as $step)

                <span class="pc-step {{ $step['completed'] ? 'done' : 'pending' }}">

                    @if($step['completed'])

                    <i
                        data-lucide="check"
                        width="12"
                        height="12"></i>

                    @else

                    <i
                        data-lucide="plus"
                        width="12"
                        height="12"></i>

                    @endif

                    {{ $step['title'] }}

                </span>

                @endforeach

            </div>

        </div>


        {{-- CTA --}}
        <a
            href="{{ route('edit-profile') }}"
            class="pc-cta-btn">
            Complete Now
            <i
                data-lucide="arrow-right"
                width="16"
                height="16"></i>
        </a>

        </div>


        @else

        {{-- ==========================================
             PROFILE 100% COMPLETE
        =========================================== --}}
        <div class="pc-card pc-card-complete">

            {{-- Avatar --}}
            <div class="pc-complete-left">

                <div class="pc-avatar-ring pc-avatar-ring-complete">

                    <img
                        src="{{ !empty($member?->photo) ? \App\Website\Services\ProfilePhotoUrl::get($member->photo) : asset('images/profile_photos/boy.jpg') }}"
                        alt="{{ $member->full_name }}"
                        class="pc-avatar">

                    <span class="pc-complete-check">
                        <i
                            data-lucide="check"
                            width="14"
                            height="14"></i>
                    </span>

                </div>

            </div>


            {{-- Profile information --}}
            <div class="pc-complete-info">

                <div class="pc-complete-name-row">

                    <h3 class="pc-complete-name">
                        {{ $member->full_name }}
                    </h3>

                    <span class="pc-complete-badge">
                        <i
                            data-lucide="badge-check"
                            width="14"
                            height="14"></i>

                        Profile Complete
                    </span>

                </div>


                @if(!empty($member->profile_id))

                <p class="pc-profile-id">
                    Profile ID:
                    <strong>{{ $member->profile_id }}</strong>
                </p>

                @endif


                <div class="pc-complete-progress">

                    <div class="pc-progress-info">

                        <span>
                            Profile completion
                        </span>

                        <strong>
                            100%
                        </strong>

                    </div>

                    <div class="pc-progress-bar-wrap">

                        <div
                            class="pc-progress-bar pc-progress-complete"
                            style="width: 100%;"
                            role="progressbar"
                            aria-valuenow="100"
                            aria-valuemin="0"
                            aria-valuemax="100"></div>

                    </div>

                </div>

            </div>


            {{-- View Profile --}}
            <a
                href="{{ route('view-my-profile')}}"
                class="pc-view-btn">
                View Profile

                <i
                    data-lucide="arrow-right"
                    width="16"
                    height="16"></i>
            </a>

        </div>

        @endif

</section>

<!-- STATS BLOCKS -->
<section class="stats-section container-xxl" aria-label="Dashboard statistics">
    <div class="stats-grid">

        <!-- Profile Views -->
        <a href="{{ url('stats-profiles?profile=profile_viewed') }}" class="stat-card pink">
            <div class="stat-card-header">
                <span class="stat-label">Profile Views</span>

                <div class="stat-icon-wrap pink">
                    <i data-lucide="eye" width="20" height="20"></i>
                </div>
            </div>

            <span class="stat-number" data-target="{{ $data['iviewed'] }}">
                {{ $data['iviewed'] }}
            </span>

            <span class="stat-trend up">
                <i data-lucide="users" width="12" height="12"></i>
                People viewed your profile
            </span>
        </a>


        <!-- Likes -->
        <a href="{{ url('stats-profiles?profile=likes') }}" class="stat-card blue">
            <div class="stat-card-header">
                <span class="stat-label">Likes</span>

                <div class="stat-icon-wrap blue">
                    <i data-lucide="heart" width="20" height="20"></i>
                </div>
            </div>

            <span class="stat-number" data-target="{{ $data['iLikes'] }}">
                {{ $data['iLikes'] }}
            </span>

            <span class="stat-trend up">
                <i data-lucide="heart-handshake" width="12" height="12"></i>
                Profiles you liked
            </span>
        </a>


        <!-- Interests -->
        <a href="{{ url('interest-box') }}" class="stat-card purple">
            <div class="stat-card-header">
                <span class="stat-label">Interests Sent</span>

                <div class="stat-icon-wrap purple">
                    <i data-lucide="send" width="20" height="20"></i>
                </div>
            </div>

            <span class="stat-number" data-target="{{ $data['interestSent'] }}">
                {{ $data['interestSent'] }}
            </span>

            <span class="stat-trend up">
                <i data-lucide="user-round-plus" width="12" height="12"></i>
                Interests sent
            </span>
        </a>


        <!-- Contacts -->
        <a href="{{ url('stats-profiles?profile=contacts') }}" class="stat-card green">
            <div class="stat-card-header">
                <span class="stat-label">Contacts Viewed</span>

                <div class="stat-icon-wrap green">
                    <i data-lucide="contact-round" width="20" height="20"></i>
                </div>
            </div>

            <span class="stat-number" data-target="{{ $data['contact'] }}">
                {{ $data['contact'] }}
            </span>

            <span class="stat-trend up">
                <i data-lucide="phone" width="12" height="12"></i>
                Contact details viewed
            </span>
        </a>

    </div>
</section>

<!-- PROFILE SECTIONS -->

@include('dashboard.home.recent-profiles', [
'recents' => $data['recents']
])

@include('dashboard.home.matching-profiles', [
'matching' => $data['matching_profiles']
])

@include('dashboard.home.verified-profiles', [
'verifiedProfiles' => $data['verifiedUsers']
])

@include('dashboard.home.who-viewed-profiles', [
'viewedMyProfile' => $data['viewed']
])

@include('dashboard.home.shortlisted-profiles', [
'shortlisted' => $data['shortlist']
])

<!-- UPGRADE BANNER -->
<section class="upgrade-banner-section container-xxl" aria-label="Upgrade membership">
    <div class="upgrade-banner">
        <div class="upgrade-banner-text">
            <h3 class="upgrade-title">Unlock Full Access</h3>
            <p>See who liked you, view contact numbers, and get priority matching with a premium plan.</p>
        </div>
        <div class="upgrade-banner-actions">
            <a href="{{route('memberships')}}" class="btn-upgrade">View Plans</a>
        </div>
    </div>
</section>

<!-- Upload Photos Modal -->
<div id="photoUploadModal" class="photo-modal-overlay" aria-hidden="true">

    <div class="photo-modal-box">

        <button id="closePhotoModal" class="photo-close-btn" type="button">
            <i data-lucide="x"></i>
        </button>

        <div class="photo-modal-header">

            <div class="photo-icon">
                <i data-lucide="images"></i>
            </div>

            <h2>Upload Photos</h2>

            <p>
                Upload clear, recent photos to make your profile more attractive.
            </p>

        </div>

        <label for="photoInput" class="photo-upload-area">

            <input
                type="file"
                id="photoInput"
                multiple
                accept="image/*"
                hidden>

            <i data-lucide="upload-cloud" class="upload-icon"></i>

            <h4>Choose Photos</h4>

            <span>
                Click here to browse your photos
            </span>

            <small>
                JPG, PNG, WEBP • Maximum 5 MB each
            </small>

        </label>

        <div id="photoPreview" class="photo-preview-grid">
            <!-- JS previews -->
        </div>

        <button class="btn-primary upload-photo-btn" id="uploadPhotos" type="button">
            <i data-lucide="upload"></i>
            <span class="upload-photo-btn-label">Upload Photos</span>
        </button>

    </div>

</div>

<!-- Search Profiles Modal -->
<div id="profileSearchModal" class="search-modal" role="dialog" aria-modal="true" aria-labelledby="profileSearchTitle" aria-hidden="true">

    <div class="search-modal-content">

        <div class="search-modal-header">
            <div>
                <h3 id="profileSearchTitle">Search profiles</h3>
                <p class="search-modal-subtitle">Search by a member's name or profile ID.</p>
            </div>
            <button class="search-close" id="closeProfileSearch" type="button" aria-label="Close profile search">
                <i data-lucide="x" width="20" height="20"></i>
            </button>
        </div>

        <div class="search-input-wrapper">

            <i data-lucide="search"></i>

            <input
                type="text"
                id="profileSearchInput"
                placeholder="e.g. Pooja or HIM10618"
                autocomplete="off"
                aria-describedby="profileSearchHint">

        </div>
        <p class="search-input-hint" id="profileSearchHint">Enter at least two characters to see matching profiles.</p>

        <div id="profileSearchResults" role="status" aria-live="polite">

            <div class="search-empty">
                Start typing to search profiles...
            </div>

        </div>

    </div>

</div>
<script>
    const uploadPhotosUrl = "{{ route('upload-photos') }}";
</script>
<script src="{{asset('assets/js/upload-photos.js')}}?v=20260827-uploading"></script>
<script src="{{asset('assets/js/search-home-member.js')}}"></script>
@endsection
