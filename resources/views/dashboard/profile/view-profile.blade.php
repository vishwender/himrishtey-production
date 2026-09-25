@extends('layouts.dashboard')

@section('title', 'Profile Detail – ' . $siteName)

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/profile-detail.css') }}?v={{ filemtime(public_path('assets/css/profile-detail.css')) }}">
@endsection

@section('content')
@php
$publicShareUrl = route('profile.share-preview', $usr->profile_id);
$publicShareText = \App\Website\Services\ProfileShareData::text($usr, $publicShareUrl);
@endphp
<!-- ===================== HERO / PHOTO CAROUSEL ===================== -->
<section class="pd-hero">

    <div class="pd-hero-carousel" id="heroCarousel">

        <!-- =========================================================
             BLURRED BACKGROUND
        ========================================================== -->
        <div
            class="pd-hero-bg"
            id="heroBackground"
            style="background-image: url('{{ $usr->photo }}');">
        </div>


        <!-- =========================================================
             DARK / LIGHT OVERLAY
        ========================================================== -->
        <div class="pd-hero-overlay-bg"></div>


        <!-- =========================================================
             MAIN PROFILE IMAGE
        ========================================================== -->
        <div class="pd-profile-image-wrapper">

            <div class="pd-profile-image">

                <img
                    id="mainProfileImage"
                    src="{{ $usr->photo }}"
                    alt="{{ $usr->full_name }}">

            </div>


            <!-- Verified badge -->
            @if (!empty($usr->member_type))
            <div
                class="pd-profile-verified"
                title="Verified Profile">
                <i
                    data-lucide="check"
                    width="22"
                    height="22"></i>
            </div>
            @endif
        </div>


        <!-- =========================================================
             SLIDE INDICATORS
            ========================================================== -->
        <!-- <div
                class="pd-slide-dots"
                id="slideDots">
            </div> -->


        <!-- =========================================================
             PREVIOUS BUTTON
            ========================================================== -->
        <!-- <button
                class="pd-slide-nav pd-slide-prev"
                id="slidePrev"
                aria-label="Previous photo">
                <i
                    data-lucide="chevron-left"
                    width="22"
                    height="22"></i>
            </button> -->


        <!-- =========================================================
             NEXT BUTTON
            ========================================================== -->
        <!-- <button
                class="pd-slide-nav pd-slide-next"
                id="slideNext"
                aria-label="Next photo">
                <i
                    data-lucide="chevron-right"
                    width="22"
                    height="22"></i>
            </button> -->


        <!-- =========================================================
             HERO INFORMATION
        ========================================================== -->
        <div class="pd-hero-info">

            <div class="pd-hero-meta">

                <p class="pd-hero-age">
                    {{$usr->age_years}} | 5'7" ft
                </p>


                <h1 class="pd-hero-name">

                    {{ $usr->full_name }} |

                    <span class="pd-hero-id">{{ $usr->profile_id }}</span>

                </h1>


                <p class="pd-hero-location">

                    <i
                        data-lucide="map-pin"
                        width="14"
                        height="14"></i>

                    {{$usr->city_living_in}},
                    {{$usr->state_living_in}}

                </p>

            </div>


            <!-- =====================================================
                 RIGHT SIDE ACTION BUTTONS
            ====================================================== -->
            <div class="pd-hero-actions">

                <!-- Like -->
                <button
                    class="pd-hero-fab"
                    id="likeBtn"
                    data-profile-id="{{$usr->id}}"
                    aria-label="Like profile"
                    title="Like">
                    <i
                        data-lucide="heart"
                        width="20"
                        height="20"></i>
                </button>


                <!-- Shortlist -->
                <!-- <button
                        class="pd-hero-fab"
                        id="shortlistBtn"
                        aria-label="Shortlist profile"
                        title="Shortlist">
                        <i
                            data-lucide="bookmark"
                            width="20"
                            height="20"></i>
                    </button> -->

            </div>

        </div>

    </div>

</section>

<!-- ===================== MAIN CONTENT ===================== -->
<div class="pd-main container-xl">
    <div class="pd-layout">

        <!-- LEFT COLUMN: Detail Sections -->
        <div class="pd-details-col">

            <!-- Premium / Verified badge strip -->
            <div class="pd-badge-strip">
                @if(!empty($usr->member_type))
                <span class="pd-badge pd-badge-verified">
                    <i data-lucide="badge-check" width="14" height="14"></i> Verified Profile
                </span>
                @endif
                <span class="pd-badge pd-badge-premium">
                    <i data-lucide="star" width="14" height="14"></i> Premium Member
                </span>
                <span class="pd-badge pd-badge-active">
                    <i data-lucide="circle" width="10" height="10"></i> Active
                </span>
            </div>

            <!-- ── BASIC DETAILS ── -->
            <div class="pd-section" id="sec-basic">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: var(--color-primary);">
                        <i data-lucide="user-circle" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">Basic Details</h2>
                </div>
                <div class="pd-section-body">
                    <p class="pd-about-text">
                        {{ $usr->about_me }}
                    </p>
                    <p class="pd-meta-line">
                        Created by <strong>{{$usr->profile_created_for}}</strong> &nbsp;·&nbsp; {{$usr->age_years}} years &nbsp;·&nbsp; Profile ID: <strong>{{$usr->profile_id }}</strong> &nbsp;·&nbsp; {{$usr->religion }} &nbsp;·&nbsp; {{$usr->cast}} &nbsp;·&nbsp; {{$usr->city_living_in}}
                    </p>
                    <div class="pd-tags">
                        <span class="pd-tag">{{$usr->marital_status}}</span>
                    </div>
                </div>
            </div>
            <!-- ── ASTRO & KUNDLI ── -->
            <div class="pd-section" id="sec-kundli">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: #4f8ef7;">
                        <i data-lucide="star" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">Astro & Kundli Details</h2>
                </div>
                <div class="pd-section-body">
                    <div class="pd-info-grid">
                        <div class="pd-info-row">
                            <span class="pd-info-label">Date of Birth</span>
                            <span class="pd-info-value">{{ $usr->birth_date ?: '-' }}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Time of Birth</span>
                            <span class="pd-info-value">{{ $usr->birth_time ?: '-' }}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Place of Birth</span>
                            <span class="pd-info-value">{{ $usr->birth_place ?: '-' }}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Manglik</span>
                            <span class="pd-info-value">{{ $usr->manglik ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── RELIGION INFORMATION ── -->
            <div class="pd-section" id="sec-religion">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: #f97316;">
                        <i data-lucide="sun" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">Religion Information</h2>
                </div>
                <div class="pd-section-body">
                    <div class="pd-info-grid">
                        <div class="pd-info-row">
                            <span class="pd-info-label">Community</span>
                            <span class="pd-info-value">{{$usr->cast}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Sub Community</span>
                            <span class="pd-info-value">{{$usr->sub_cast}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Gotra</span>
                            <span class="pd-info-value">{{$usr->gotra}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Native Place</span>
                            <span class="pd-info-value">{{$usr->native_place}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Mother Tongue</span>
                            <span class="pd-info-value">{{$usr->mother_tongue}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── CONTACT DETAILS ── -->
            <div class="pd-section" id="sec-contact">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: #ea4c2a;">
                        <i data-lucide="phone" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">Contact Details</h2>
                </div>
                <div class="pd-section-body">
                    <div class="pd-info-grid">
                        <div class="pd-info-row pd-contact-row">
                            <div>
                                <span class="pd-info-label">Contact Number</span>
                                <span class="pd-info-value{{ $usr->contact_unlocked ? '' : ' pd-locked' }}" id="mobileValue">
                                    @unless($usr->contact_unlocked)<i data-lucide="lock" width="14" height="14"></i>@endunless{{ $usr->contact_unlocked ? ($usr->mobile_number ?: '-') : $usr->mobile_number_masked }}
                                </span>
                            </div>
                            @unless($usr->contact_unlocked)<i data-lucide="lock" width="16" height="16" class="pd-row-lock"></i>@endunless
                        </div>
                        <div class="pd-info-row pd-contact-row">
                            <div>
                                <span class="pd-info-label">WhatsApp</span>
                                <span class="pd-info-value{{ $usr->contact_unlocked ? '' : ' pd-locked' }}" id="waValue">
                                    @unless($usr->contact_unlocked)<i data-lucide="lock" width="14" height="14"></i>@endunless{{ $usr->contact_unlocked ? ($usr->whatsapp_number ?: '-') : $usr->whatsapp_number_masked }}
                                </span>
                            </div>
                            @unless($usr->contact_unlocked)<i data-lucide="lock" width="16" height="16" class="pd-row-lock"></i>@endunless
                        </div>
                        <div class="pd-info-row pd-contact-row">
                            <div>
                                <span class="pd-info-label">Email</span>
                                <span class="pd-info-value{{ $usr->contact_unlocked ? '' : ' pd-locked' }}" id="emailValue">
                                    @unless($usr->contact_unlocked)<i data-lucide="lock" width="14" height="14"></i>@endunless{{ $usr->contact_unlocked ? ($usr->email ?: '-') : $usr->email_masked }}
                                </span>
                            </div>
                            @unless($usr->contact_unlocked)<i data-lucide="lock" width="16" height="16" class="pd-row-lock"></i>@endunless
                        </div>
                    </div>
                    @unless($usr->contact_unlocked)
                    <div class="pd-unlock-strip" id="contactUnlock">
                        <span>Want to get full contact information?</span>
                        <button class="pd-btn-unlock pd-btn-unlock-orange" onclick="openUnlockModal('contact')">
                            <i data-lucide="lock-open" width="15" height="15"></i> Unlock Now
                        </button>
                    </div>
                    @endunless
                </div>
            </div>

            <!-- ── EDUCATION & CAREER ── -->
            <div class="pd-section" id="sec-edu">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: #16a34a;">
                        <i data-lucide="graduation-cap" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">Education & Career</h2>
                </div>
                <div class="pd-section-body">
                    <div class="pd-info-grid">
                        <div class="pd-info-row pd-info-row--full">
                            <span class="pd-info-label">About Education & Career</span>
                            <span class="pd-info-value">{{$usr->about_my_education}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Education</span>
                            <span class="pd-info-value">{{$usr->education}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Other Qualification</span>
                            <span class="pd-info-value">{{$usr->any_other_qualifications}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Employed In</span>
                            <span class="pd-info-value">{{$usr->employed_in}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Occupation</span>
                            <span class="pd-info-value">{{$usr->occupation}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Currently Working At</span>
                            <span class="pd-info-value">{{$usr->organization_name}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Job Location</span>
                            <span class="pd-info-value">{{$usr->job_location}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Annual Income</span>
                            <span class="pd-info-value">{{$usr->annual_income}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── FAMILY DETAILS ── -->
            <div class="pd-section" id="sec-family">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: #0d9488;">
                        <i data-lucide="users" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">Family Details</h2>
                </div>
                <div class="pd-section-body">
                    <div class="pd-info-grid">
                        <div class="pd-info-row pd-info-row--full">
                            <span class="pd-info-label">About My Family</span>
                            <span class="pd-info-value">{{$usr->about_family}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Father's Occupation</span>
                            <span class="pd-info-value">{{$usr->father_occupation}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Mother's Occupation</span>
                            <span class="pd-info-value">{{$usr->mother_occupation}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Brothers</span>
                            <span class="pd-info-value">{{$usr->no_of_brothers}} ({{ $usr->married_brothers }} Married)</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Sisters</span>
                            <span class="pd-info-value">{{$usr->no_of_sisters}} ({{ $usr->married_sisters }} Married)</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Native Place</span>
                            <span class="pd-info-value">{{$usr->native_place}},{{$usr->state_living_in}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Family Type</span>
                            <span class="pd-info-value">{{$usr->family_type}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── LIFESTYLE ── -->
            <div class="pd-section" id="sec-lifestyle">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: #ca8a04;">
                        <i data-lucide="coffee" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">Lifestyle</h2>
                </div>
                <div class="pd-section-body">
                    <div class="pd-lifestyle-grid">
                        <div class="pd-lifestyle-chip">
                            <i data-lucide="utensils" width="16" height="16"></i>
                            <div>
                                <span class="pd-lc-label">Diet</span>
                                <span class="pd-lc-value">{{$usr->diet}}</span>
                            </div>
                        </div>
                        <div class="pd-lifestyle-chip">
                            <i data-lucide="cigarette-off" width="16" height="16"></i>
                            <div>
                                <span class="pd-lc-label">Smoking</span>
                                <span class="pd-lc-value">{{$usr->is_smoking}}</span>
                            </div>
                        </div>
                        <div class="pd-lifestyle-chip">
                            <i data-lucide="wine-off" width="16" height="16"></i>
                            <div>
                                <span class="pd-lc-label">Drinking</span>
                                <span class="pd-lc-value">{{$usr->is_drinking}}</span>
                            </div>
                        </div>
                        <div class="pd-lifestyle-chip">
                            <i data-lucide="accessibility" width="16" height="16"></i>
                            <div>
                                <span class="pd-lc-label">Disability</span>
                                <span class="pd-lc-value">{{$usr->any_disability}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── PARTNER PREFERENCES ── -->
            <div class="pd-section" id="sec-partner">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: #e11d48;">
                        <i data-lucide="heart-handshake" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">Partner Preferences</h2>
                </div>
                <div class="pd-section-body">
                    <div class="pd-info-grid">
                        <div class="pd-info-row pd-info-row--full">
                            <span class="pd-info-label">About My Partner</span>
                            <span class="pd-info-value">{{$usr->about_my_partner}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Age Range</span>
                            <span class="pd-info-value">{{$usr->partner_age_from}} - {{$usr->partner_age_to}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Height Range</span>
                            <span class="pd-info-value">{{$usr->partner_height_from}} - {{$usr->partner_height_to}}"</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Marital Status</span>
                            <span class="pd-info-value">{{$usr->looking_for}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Religion & Mother Tongue</span>
                            <span class="pd-info-value">{{$usr->partner_religion}} | {{$usr->partner_mothertongue}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Community</span>
                            <span class="pd-info-value">{{$usr->partner_cast}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Is Manglik</span>
                            <span class="pd-info-value">{{$usr->is_partner_manglik}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Highest Qualification</span>
                            <span class="pd-info-value">{{$usr->partner_education}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Partner Occupation</span>
                            <span class="pd-info-value">{{$usr->partner_occupation}}</span>
                        </div>
                        <div class="pd-info-row">
                            <span class="pd-info-label">Annual Income</span>
                            <span class="pd-info-value">{{$usr->partner_annual_income_from}} - {{$usr->partner_annual_income_to}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── ABOUT ── -->
            <div class="pd-section" id="sec-about">
                <div class="pd-section-header">
                    <div class="pd-section-icon" style="--icon-color: #6366f1;">
                        <i data-lucide="file-text" width="20" height="20"></i>
                    </div>
                    <h2 class="pd-section-title">About</h2>
                </div>
                <div class="pd-section-body">
                    <p class="pd-about-text">
                        Thank you for visiting my profile. I am {{$usr->height}} and {{$usr->age_years}} years old. I belong to {{$usr->city_living_in}}, {{$usr->state_living_in}}. I am looking for a suitable match. If you find my profile suitable, please contact me.
                    </p>
                </div>
            </div>

            <!-- ── REPORT ── -->
            <div class="pd-report-wrap">
                <button class="pd-report-btn" id="reportBtn">
                    <i data-lucide="flag" width="16" height="16"></i>
                    Report this profile
                </button>
            </div>

        </div><!-- / pd-details-col -->

        <!-- Action card: sticky sidebar on desktop, above details on mobile -->
        <aside class="pd-aside">
            <div class="pd-action-card">
                <div class="pd-action-profile-thumb">
                    <div class="pd-thumb-placeholder">
                        <i data-lucide="user" width="36" height="36"></i>
                    </div>
                    <div class="pd-action-name">
                        <strong>{{$usr->full_name}}</strong>
                        <span>{{$usr->age_years}} yrs · {{$usr->city_living_in}}</span>
                    </div>
                </div>

                <div class="pd-action-btns" id="actionBtns">

                    <button type="button" class="pd-btn-shortlist" id="galleryActionBtn">
                        <i data-lucide="images" width="17" height="17"></i>
                        View Gallery
                    </button>

                    @if($usr->is_free_member)

                    <a href="{{ route('memberships') }}" class="pd-btn-interest">
                        <i data-lucide="crown" width="17" height="17"></i>
                        Activate Membership to Send Interest
                    </a>

                    @else

                    <button
                        class="pd-btn-interest"
                        id="sendInterestBtn"
                        data-profile-id="{{ $usr->id }}"
                        onclick="handleInterestAction()">
                        <i data-lucide="send" width="17" height="17"></i>
                        Send Interest
                    </button>



                    <!-- Shortlist -->
                    <button
                        class="pd-btn-shortlist"
                        id="asideShortlistBtn"
                        data-profile-id="{{ $usr->id }}"
                        onclick="toggleShortlist()">
                        <i data-lucide="bookmark" width="17" height="17"></i>
                        Shortlist
                    </button>
                    @endif
                </div>
                <div class="pd-action-divider"></div>

                <!-- Quick info -->
                <ul class="pd-quick-info" role="list">
                    <li>
                        <i data-lucide="calendar" width="15" height="15"></i>
                        <span>{{$usr->age_years}} years old</span>
                    </li>
                    <li>
                        <i data-lucide="ruler" width="15" height="15"></i>
                        <span>{{$usr->height}} ft</span>
                    </li>
                    <li>
                        <i data-lucide="map-pin" width="15" height="15"></i>
                        <span>{{$usr->city_living_in}}, {{$usr->state_living_in}}</span>
                    </li>
                    <li>
                        <i data-lucide="briefcase" width="15" height="15"></i>
                        <span>{{$usr->occupation}}</span>
                    </li>
                    <li>
                        <i data-lucide="graduation-cap" width="15" height="15"></i>
                        <span>{{$usr->education}}</span>
                    </li>
                    <li>
                        <i data-lucide="users" width="15" height="15"></i>
                        <span>{{$usr->cast}} · {{$usr->religion}}</span>
                    </li>
                </ul>

                <div class="pd-action-divider"></div>

                <button type="button" id="shareBtn" class="pd-btn-share-profile"

                    data-url="{{ $publicShareUrl }}"
                    data-share-text="{{ $publicShareText }}">
                    <i data-lucide="share-2" width="16" height="16"></i>
                    Share Profile
                </button>

            </div>
            @if($usr->is_free_member)
            <!-- Upgrade card (shown when plan not active) -->
            <div class="pd-upgrade-card" id="upgradeCard">
                <div class="pd-upgrade-icon">
                    <i data-lucide="crown" width="24" height="24"></i>
                </div>
                <h3>Activate Membership</h3>
                <p>Unlock contact details and send interests with a Premium plan.</p>
                <a href="{{route('memberships')}}" class="pd-btn-upgrade">
                    <i data-lucide="zap" width="15" height="15"></i>
                    View Plans
                </a>
            </div>
            @endif
        </aside>

    </div><!-- / pd-layout -->
</div>

<!-- ===================== GALLERY LIGHTBOX ===================== -->
<dialog id="profileShareDialog" class="pd-share-dialog" aria-labelledby="profileShareTitle" aria-describedby="profileShareDescription">
    <div class="pd-share-heading">
        <span class="pd-share-symbol"><i data-lucide="share-2" aria-hidden="true"></i></span>
        <form method="dialog"><button class="pd-share-close" aria-label="Close share dialog"><i data-lucide="x" aria-hidden="true"></i></button></form>
    </div>
    <h2 id="profileShareTitle">Share this profile</h2>
    <p id="profileShareDescription">A thoughtful introduction could be the start of something special.</p>
    <div class="pd-share-member">
        <img src="{{ $usr->photo }}" alt="" width="56" height="56">
        <div><strong>{{ $usr->full_name }}</strong><span>{{ $usr->profile_id }} · {{ $usr->city_living_in }}</span></div>
    </div>
    <label for="profileShareUrl">Profile link</label>
    <div class="pd-share-link-row">
        <input id="profileShareUrl" type="url" readonly onclick="this.select()">
        <button type="button" class="pd-share-copy" onclick="copyProfileLink()"><i data-lucide="copy" width="16" aria-hidden="true"></i> Copy link</button>
    </div>
    <p class="pd-share-hint">Anyone with this link can view the profile preview.</p>
    <div class="pd-share-actions">
        <a id="profileShareWhatsApp" class="pd-share-whatsapp" target="_blank" rel="noopener noreferrer"><i data-lucide="message-circle" width="18" aria-hidden="true"></i> Share via WhatsApp</a>
        <a id="profileSharePreview" class="pd-share-preview" target="_blank" rel="noopener noreferrer">Open preview <i data-lucide="external-link" width="16" aria-hidden="true"></i></a>
    </div>
    <p class="pd-share-hint">WhatsApp includes the full profile summary and preview link.</p>
    <p id="profileShareStatus" role="status" aria-live="polite"></p>
</dialog>

<div class="pd-gallery-overlay" id="galleryOverlay" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Profile Gallery">
    <div class="pd-gallery-modal {{ $galleryPhotos->count() === 1 ? 'pd-gallery-modal-single' : '' }}">
        <button class="pd-gallery-close" id="galleryClose" aria-label="Close gallery">
            <i data-lucide="x" width="22" height="22"></i>
        </button>
        <div class="pd-gallery-grid" id="galleryGrid">
            @foreach ($galleryPhotos as $photoUrl)
            <div class="pd-gallery-item">
                <img class="pd-gallery-image" src="{{ $photoUrl }}" alt="{{ $usr->full_name }} gallery photo" loading="lazy">
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- ===================== UNLOCK MODAL ===================== -->
<div class="pd-modal-overlay" id="unlockModalOverlay" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="pd-modal">
        <div class="pd-modal-header">
            <h3 id="unlockModalTitle">Unlock Profile</h3>
            <button class="pd-modal-close" onclick="closeUnlockModal('contact')" aria-label="Close">
                <i data-lucide="x" width="20" height="20"></i>
            </button>
        </div>
        <div class="pd-modal-body">
            <div class="pd-modal-profile">
                <div class="pd-modal-avatar">
                    <i data-lucide="user" width="28" height="28"></i>
                </div>
                <strong>{{ $usr->full_name }}</strong>
            </div>
            <div class="pd-modal-row">
                <span>Profile view price</span>
                <span>₹ {{ $usr->profile_view_price }}</span>
            </div>
            <div class="pd-modal-wallet">
                <div>
                    <span>Wallet Balance</span>
                    <small class="pd-wallet-low" id="walletLowNote" style="display:none;">Low balance</small>
                </div>

                <span id="walletBalance">₹ {{ $wallet->wallet_balance ?? 0 }}</span>
            </div>
        </div>
        <div class="pd-modal-footer">
            <button class="pd-modal-cancel" onclick="closeUnlockModal()">Cancel</button>
            <button class="pd-modal-confirm" data-profile-id="{{ $usr->id }}"
                data-unlock-url="{{ route('unlock.contact', $usr->id) }}"
                data-unlock-price="{{ $usr->profile_view_price }}" id="unlockConfirmBtn" onclick="confirmUnlock()">
                Unlock
            </button>
        </div>
    </div>
</div>

<!-- ===================== REPORT BOTTOM SHEET ===================== -->
<div class="pd-sheet-overlay" id="reportSheetOverlay" aria-hidden="true">
    <div class="pd-sheet" id="reportSheet" role="dialog" aria-modal="true" aria-label="Report Profile">
        <div class="pd-sheet-handle"></div>
        <h3 class="pd-sheet-title">Report Profile</h3>
        <p class="pd-sheet-subtitle">Why are you reporting this profile? Your report is anonymous.</p>
        <ul class="pd-report-list" role="list">
            <li><button onclick="submitReport(this)">I don't like this profile.</button></li>
            <li><button onclick="submitReport(this)">Bullying or unwanted content.</button></li>
            <li><button onclick="submitReport(this)">Violence, hate or exploitation.</button></li>
            <li><button onclick="submitReport(this)">Selling or promoting restricted items.</button></li>
            <li><button onclick="submitReport(this)">Nudity or sexual activity.</button></li>
            <li><button onclick="submitReport(this)">Scam, fraud or spam.</button></li>
            <li><button onclick="submitReport(this)">False Information.</button></li>
        </ul>
    </div>
</div>

<!-- Toast notification -->
<div class="pd-toast" id="pdToast" role="alert" aria-live="polite"></div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/profile-detail.js') }}?v={{ filemtime(public_path('assets/js/profile-detail.js')) }}"></script>
@endsection