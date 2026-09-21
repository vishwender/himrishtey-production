@extends('layouts.dashboard')

@section('title', 'Search By Profile Id - HimRishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/search-by-id.css') }}" />
@endsection

@section('content')
<!-- ===== MAIN ===== -->
<main class="main-content" id="sbid-main">
    <div class="sbid-container">

        <!-- Page Header -->
        <div class="sbid-page-header">
            <div class="sbid-page-header-icon" aria-hidden="true">
                <i data-lucide="id-card" width="22" height="22"></i>
            </div>
            <div>
                <h1 class="sbid-page-title">Search by Profile ID</h1>
                <p class="sbid-page-subtitle">Enter a HimRishtey profile ID to find a specific member</p>
            </div>
        </div>

        <!-- Search Card -->
        <div class="sbid-search-card">
            <form method="GET" action="{{route('search-by-profile-id')}}">
                <div class="sbid-search-card-body">
                    <label class="sbid-label" for="profileIdInput">
                        <i data-lucide="hash" width="14" height="14"></i>
                        Profile ID
                    </label>
                    <div class="sbid-input-row">
                        <div class="sbid-input-wrap">
                            <i data-lucide="search" width="18" height="18" class="sbid-input-icon"></i>
                            <input
                                type="text"
                                id="profileIdInput"
                                name="profile_id"
                                class="sbid-input"
                                placeholder="e.g. HIM10234"
                                autocomplete="off"
                                autocorrect="off"
                                spellcheck="false"
                                maxlength="20"
                                aria-label="Enter profile ID" />
                            <button type="button" class="sbid-input-clear" id="sbidClearInput" aria-label="Clear input" style="display:none;">
                                <i data-lucide="x" width="14" height="14"></i>
                            </button>
                        </div>
                        <button type="button" class="sbid-search-btn" id="sbidSearchBtn" aria-label="Search profile">
                            <span class="sbid-btn-text">Search</span>
                            <span class="sbid-btn-icon"><i data-lucide="arrow-right" width="16" height="16"></i></span>
                            <!-- Loading spinner (hidden by default) -->
                            <span class="sbid-btn-spinner" aria-hidden="true" style="display:none;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="sbid-spin">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    <p class="sbid-hint">
                        <i data-lucide="info" width="12" height="12"></i>
                        Profile IDs are in the format <strong>HIMXXXXX</strong>. You can find it on any member's profile card.
                    </p>
                </div>
            </form>
        </div>

        <!-- ===== STATES AREA ===== -->

        <!-- IDLE STATE (default) -->
        <div class="sbid-state sbid-state-idle" id="stateIdle">
            <div class="sbid-idle-illustration" aria-hidden="true">
                <svg viewBox="0 0 160 120" fill="none" xmlns="http://www.w3.org/2000/svg" width="160" height="120">
                    <ellipse cx="80" cy="110" rx="60" ry="8" fill="var(--color-surface-offset)" />
                    <rect x="30" y="20" width="100" height="76" rx="12" fill="var(--color-surface)" stroke="var(--color-border)" stroke-width="1.5" />
                    <circle cx="80" cy="50" r="18" fill="var(--color-primary-light,rgba(217,39,104,0.12))" />
                    <circle cx="80" cy="47" r="10" fill="var(--color-primary-light,rgba(217,39,104,0.2))" />
                    <ellipse cx="80" cy="63" rx="14" ry="6" fill="var(--color-primary-light,rgba(217,39,104,0.12))" />
                    <rect x="50" y="72" width="60" height="5" rx="2.5" fill="var(--color-surface-offset)" />
                    <rect x="60" y="81" width="40" height="4" rx="2" fill="var(--color-surface-offset)" />
                    <!-- Search glass -->
                    <circle cx="118" cy="30" r="12" stroke="var(--color-primary)" stroke-width="2.5" fill="none" />
                    <line x1="126" y1="38" x2="133" y2="45" stroke="var(--color-primary)" stroke-width="2.5" stroke-linecap="round" />
                </svg>
            </div>
            <p class="sbid-idle-text">Enter a profile ID above to search for a member</p>
        </div>

        <!-- LOADING STATE -->
        <div class="sbid-state sbid-state-loading" id="stateLoading" style="display:none;" aria-live="polite" aria-label="Searching...">
            <div class="sbid-skeleton-card">
                <div class="sbid-skeleton-avatar"></div>
                <div class="sbid-skeleton-lines">
                    <div class="sbid-skeleton sbid-skeleton-name"></div>
                    <div class="sbid-skeleton sbid-skeleton-sub"></div>
                    <div class="sbid-skeleton sbid-skeleton-sub2"></div>
                </div>
            </div>
            <p class="sbid-loading-text">Searching for profile…</p>
        </div>

        <!-- NOT FOUND STATE -->
        <div class="sbid-state sbid-state-notfound" id="stateNotFound" style="display:none;" aria-live="polite">
            <div class="sbid-notfound-icon" aria-hidden="true">
                <i data-lucide="user-x" width="32" height="32"></i>
            </div>
            <h2 class="sbid-notfound-title">Profile Not Found</h2>
            <p class="sbid-notfound-desc">No member found with ID <strong id="notFoundId"></strong>. Please check and try again.</p>
            <button type="button" class="sbid-try-again-btn" id="sbidTryAgain">
                <i data-lucide="rotate-ccw" width="14" height="14"></i> Try Again
            </button>
        </div>

        <!-- RESULT STATE -->
        <div class="sbid-state sbid-state-result" id="stateResult" style="display:none;" aria-live="polite">
            <p class="sbid-result-label">Search result for <strong id="resultIdLabel"></strong></p>

            <!-- Profile Result Card -->
            <div class="sbid-profile-card" id="sbidProfileCard" tabindex="0" role="article" aria-label="Search result profile">

                <!-- Cover + Avatar -->
                <div class="sbid-profile-cover">
                    <img src="" alt="" id="resultCoverImg" class="sbid-cover-img" width="680" height="160" loading="lazy" />
                    <div class="sbid-cover-overlay"></div>
                </div>

                <div class="sbid-profile-body">
                    <div class="sbid-profile-top">
                        <div class="sbid-profile-avatar-wrap">
                            <img src="" alt="" id="resultAvatar" class="sbid-profile-avatar" width="80" height="80" loading="lazy" />
                            <span class="sbid-online-badge" id="resultOnlineBadge" style="display:none;" aria-label="Online now"></span>
                        </div>
                        <div class="sbid-profile-actions">
                            <button type="button" class="sbid-action-btn sbid-btn-interest" id="sbidInterestBtn" aria-label="Send interest">
                                <i data-lucide="heart" width="16" height="16"></i>
                                <span>Interest</span>
                            </button>
                            <button type="button" class="sbid-action-btn sbid-btn-shortlist" id="sbidShortlistBtn" aria-label="Shortlist profile">
                                <i data-lucide="bookmark" width="16" height="16"></i>
                                <span>Shortlist</span>
                            </button>
                            <button type="button" class="sbid-action-btn sbid-btn-message" id="sbidMessageBtn" aria-label="Send message">
                                <i data-lucide="message-circle" width="16" height="16"></i>
                                <span>Message</span>
                            </button>
                        </div>
                    </div>

                    <!-- Name / Basic Info -->
                    <div class="sbid-profile-info">
                        <div class="sbid-profile-name-row">
                            <h2 class="sbid-profile-name" id="resultName">—</h2>
                            <span class="sbid-verified-badge" id="resultVerifiedBadge" style="display:none;" aria-label="Verified profile">
                                <i data-lucide="badge-check" width="14" height="14"></i> Verified
                            </span>
                        </div>
                        <p class="sbid-profile-id-tag" id="resultIdTag">—</p>

                        <!-- Quick Stats Row -->
                        <div class="sbid-stats-row" id="resultStatsRow">
                            <!-- populated by JS -->
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="sbid-details-grid" id="resultDetailsGrid">
                        <!-- populated by JS -->
                    </div>

                    <!-- View Full Profile CTA -->
                    <a href="#" class="sbid-view-full-btn" id="sbidViewFullBtn" aria-label="View full profile">
                        View Full Profile
                        <i data-lucide="arrow-right" width="16" height="16"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection

@section('scripts')
<!-- Page JS -->
<script src="{{ asset('assets/js/search-by-id.js') }}"></script>
@endsection