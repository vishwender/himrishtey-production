@extends('layouts.dashboard')

@section('title', 'My Profile – ' . $siteName)

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/profile-detail.css') }}">
@endsection

@section('content')

    <!-- ===================== HERO / PHOTO CAROUSEL ===================== -->
    <section class="pd-hero">

        <div class="pd-hero-carousel" id="heroCarousel">

            <div class="pd-hero-bg"
                style="background-image: url('{{ $profile->profile_photo_url }}');"></div>
            <div class="pd-hero-overlay-bg"></div>

            <div class="pd-profile-image-wrapper">
                <div class="pd-profile-image">
                    <img src="{{ $profile->profile_photo_url }}"
                        alt="{{ filled($profile->full_name) ? $profile->full_name : 'Not provided' }}"
                        loading="eager"
                        onerror="this.onerror=null;this.src='{{ asset('images/profile_photos/' . ($profile->gender === 'Female' ? 'girl.jpg' : 'boy.jpg')) }}';">
                </div>
                @if($profile->member_type === 'Verified')
                    <span class="pd-profile-verified" title="Verified Profile">
                        <i data-lucide="check" width="22" height="22"></i>
                    </span>
                @endif
            </div>

            <div class="pd-hero-info">

                <div class="pd-hero-meta">

                    <p class="pd-hero-age">{{ $profile->age_years !== null ? $profile->age_years . ' years' : 'Age not provided' }} · {{ filled($profile->formatted_height) ? $profile->formatted_height : 'Not provided' }}</p>

                    <h1 class="pd-hero-name">
                        {{ filled($profile->full_name) ? $profile->full_name : 'Not provided' }}
                        <span class="pd-hero-id">| {{ filled($profile->profile_id) ? $profile->profile_id : 'Not provided' }}</span>
                    </h1>

                    <p class="pd-hero-location">
                        <i data-lucide="map-pin" width="14" height="14"></i>
                        {{ filled($profile->city_living_in) ? $profile->city_living_in : 'Not provided' }}, {{ filled($profile->state_living_in) ? $profile->state_living_in : 'Not provided' }}

                    </p>

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
                    @if($profile->member_type === 'Verified')
                        <span class="pd-badge pd-badge-verified"><i data-lucide="badge-check" width="14" height="14"></i> Verified Profile</span>
                    @endif
                    @if($profile->membershipPlan)
                        <span class="pd-badge pd-badge-premium"><i data-lucide="star" width="14" height="14"></i> {{ $profile->membershipPlan->plan_name }}</span>
                    @endif
                    <span class="pd-badge {{ strtolower((string) $profile->active) === 'yes' ? 'pd-badge-active' : '' }}">
                        <i data-lucide="circle" width="10" height="10"></i> {{ strtolower((string) $profile->active) === 'yes' ? 'Active' : 'Inactive' }}
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
                            {{ filled($profile->about_me) ? $profile->about_me : 'Not provided' }}
                        </p>
                        <p class="pd-meta-line">
                            Profile created for <strong>{{ filled($profile->profile_created_for) ? $profile->profile_created_for : 'Not provided' }}</strong> &nbsp;·&nbsp; {{ $profile->age_years !== null ? $profile->age_years . ' years' : 'Age not provided' }} &nbsp;·&nbsp; Profile ID: <strong>{{ filled($profile->profile_id) ? $profile->profile_id : 'Not provided' }}</strong> &nbsp;·&nbsp; {{ filled($profile->religion) ? $profile->religion : 'Not provided' }} &nbsp;·&nbsp; {{ filled($profile->cast) ? $profile->cast : 'Not provided' }} &nbsp;·&nbsp; {{ filled($profile->city_living_in) ? $profile->city_living_in : 'Not provided' }}
                        </p>
                        <div class="pd-tags">
                            <span class="pd-tag">{{ filled($profile->marital_status) ? $profile->marital_status : 'Not provided' }}</span>
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
                                <span class="pd-info-value">{{ filled($profile->date) ? $profile->date : 'Not provided' }}</span>
                            </div>
                            <div class="pd-info-row">
                                <span class="pd-info-label">Time of Birth</span>
                                <span class="pd-info-value" id="tobValue">
                                    {{ filled($profile->time) ? $profile->time : 'Not provided' }}
                                </span>
                            </div>
                            <div class="pd-info-row">
                                <span class="pd-info-label">Place of Birth</span>
                                <span class="pd-info-value" id="pobValue">
                                    {{ filled($profile->birth_place) ? $profile->birth_place : 'Not provided' }}
                                </span>
                            </div>
                            <div class="pd-info-row">
                                <span class="pd-info-label">Manglik</span>
                                <span class="pd-info-value">{{ filled($profile->manglik) ? $profile->manglik : 'Not provided' }}</span>
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
                                    <span class="pd-info-value">{{ filled($profile->cast) ? $profile->cast : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Sub Community</span>
                                    <span class="pd-info-value">{{ filled($profile->sub_cast) ? $profile->sub_cast : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Gotra</span>
                                    <span class="pd-info-value">{{ filled($profile->gotra) ? $profile->gotra : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Native Place</span>
                                    <span class="pd-info-value">{{ filled($profile->native_place) ? $profile->native_place : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Mother Tongue</span>
                                    <span class="pd-info-value">{{ filled($profile->mother_tongue) ? $profile->mother_tongue : 'Not provided' }}</span>
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
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Contact Number</span>
                                    <span class="pd-info-value" id="mobileValue">
                                        {{ filled($profile->mobile_number) ? $profile->mobile_number : 'Not provided' }}
                                    </span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">WhatsApp Number</span>
                                    <span class="pd-info-value" id="waValue">
                                        {{ filled($profile->whatsapp_number) ? $profile->whatsapp_number : 'Not provided' }}
                                    </span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Alternate Number</span>
                                    <span class="pd-info-value" id="alternateNumberValue">
                                        {{ filled($profile->alternate_number) ? $profile->alternate_number : 'Not provided' }}
                                    </span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Email</span>
                                    <span class="pd-info-value" id="emailValue">
                                        {{ filled($profile->email) ? $profile->email : 'Not provided' }}
                                    </span>
                                </div>
                            </div>
                            <!-- <div class="pd-unlock-strip" id="contactUnlock">
                                <span>Want to get full contact information?</span>
                                <button class="pd-btn-unlock pd-btn-unlock-orange" onclick="openUnlockModal('contact')">
                                    <i data-lucide="lock-open" width="15" height="15"></i> Unlock Now
                                </button>
                            </div> -->
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
                                    <span class="pd-info-value">{{ filled($profile->about_my_education) ? $profile->about_my_education : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Education</span>
                                    <span class="pd-info-value">{{ filled($profile->education) ? $profile->education : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">About My Career</span>
                                    <span class="pd-info-value">{{ filled($profile->about_my_career) ? $profile->about_my_career : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Other Qualification</span>
                                    <span class="pd-info-value">{{ filled($profile->any_other_qualifications) ? $profile->any_other_qualifications : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Employed In</span>
                                    <span class="pd-info-value">{{ filled($profile->employed_in) ? $profile->employed_in : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Occupation</span>
                                    <span class="pd-info-value">{{ filled($profile->occupation) ? $profile->occupation : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Designation</span>
                                    <span class="pd-info-value">{{ filled($profile->designation) ? $profile->designation : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Currently Working At</span>
                                    <span class="pd-info-value">{{ filled($profile->organization_name) ? $profile->organization_name : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Job Location</span>
                                    <span class="pd-info-value">{{ filled($profile->job_location) ? $profile->job_location : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Annual Income</span>
                                    <span class="pd-info-value">{{ filled($profile->annual_income) ? $profile->annual_income : 'Not provided' }}</span>
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
                                    <span class="pd-info-value">{{ filled($profile->about_family) ? $profile->about_family : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Father's Name</span>
                                    <span class="pd-info-value">{{ filled($profile->father_name) ? $profile->father_name : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Mother's Name</span>
                                    <span class="pd-info-value">{{ filled($profile->mother_name) ? $profile->mother_name : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Family Status</span>
                                    <span class="pd-info-value">{{ filled($profile->family_status) ? $profile->family_status : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Father's Occupation</span>
                                    <span class="pd-info-value">{{ filled($profile->father_occupation) ? $profile->father_occupation : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Mother's Occupation</span>
                                    <span class="pd-info-value">{{ filled($profile->mother_occupation) ? $profile->mother_occupation : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Brothers</span>
                                    <span class="pd-info-value">{{ filled($profile->no_of_brothers) ? $profile->no_of_brothers : 'Not provided' }} | ( {{ filled($profile->married_brothers) ? $profile->married_brothers : 'Not provided' }} Married)</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Sisters</span>
                                    <span class="pd-info-value">{{ filled($profile->no_of_sisters) ? $profile->no_of_sisters : 'Not provided' }} | ( {{ filled($profile->married_sisters) ? $profile->married_sisters : 'Not provided' }} Married)</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Native Place</span>
                                    <span class="pd-info-value">{{ filled($profile->native_place) ? $profile->native_place : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Family Type</span>
                                    <span class="pd-info-value">{{ filled($profile->family_type) ? $profile->family_type : 'Not provided' }}</span>
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
                                        <span class="pd-lc-value">{{ filled($profile->diet) ? $profile->diet : 'Not provided' }}</span>
                                    </div>
                                </div>
                                <div class="pd-lifestyle-chip">
                                    <i data-lucide="cigarette-off" width="16" height="16"></i>
                                    <div>
                                        <span class="pd-lc-label">Smoking</span>
                                        <span class="pd-lc-value">{{ filled($profile->is_smoking) ? $profile->is_smoking : 'Not provided' }}</span>
                                    </div>
                                </div>
                                <div class="pd-lifestyle-chip">
                                    <i data-lucide="wine-off" width="16" height="16"></i>
                                    <div>
                                        <span class="pd-lc-label">Drinking</span>
                                        <span class="pd-lc-value">{{ filled($profile->is_drinking) ? $profile->is_drinking : 'Not provided' }}</span>
                                    </div>
                                </div>
                                <div class="pd-lifestyle-chip">
                                    <i data-lucide="accessibility" width="16" height="16"></i>
                                    <div>
                                        <span class="pd-lc-label">Disability</span>
                                        <span class="pd-lc-value">{{ filled($profile->any_disability) ? $profile->any_disability : 'Not provided' }}</span>
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
                                    <span class="pd-info-value">{{ filled($profile->about_my_partner) ? $profile->about_my_partner : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Age Range</span>
                                    <span class="pd-info-value">{{ filled($profile->partner_age_from) ? $profile->partner_age_from : 'Not provided' }} - {{ filled($profile->partner_age_to) ? $profile->partner_age_to : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Height Range</span>
                                    <span class="pd-info-value">{{ \App\Support\HeightFormatter::formatPartnerRange($profile->partner_height_from) }} – {{ \App\Support\HeightFormatter::formatPartnerRange($profile->partner_height_to) }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Marital Status</span>
                                    <span class="pd-info-value">{{ filled($profile->looking_for) ? $profile->looking_for : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Religion</span>
                                    <span class="pd-info-value">{{ filled($profile->partner_religion) ? $profile->partner_religion : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Mother Tongue</span>
                                    <span class="pd-info-value">{{ filled($profile->partner_mothertongue) ? $profile->partner_mothertongue : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Community</span>
                                    <span class="pd-info-value">{{ filled($profile->partner_cast) ? $profile->partner_cast : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Is Manglik</span>
                                    <span class="pd-info-value">{{ filled($profile->is_partner_manglik) ? $profile->is_partner_manglik : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Highest Qualification</span>
                                    <span class="pd-info-value">{{ filled($profile->partner_education) ? $profile->partner_education : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Partner Occupation</span>
                                    <span class="pd-info-value">{{ filled($profile->partner_occupation) ? $profile->partner_occupation : 'Not provided' }}</span>
                                </div>
                                <div class="pd-info-row">
                                    <span class="pd-info-label">Annual Income</span>
                                    <span class="pd-info-value">{{ filled($profile->partner_annual_income_from) ? $profile->partner_annual_income_from : 'Not provided' }} - {{ filled($profile->partner_annual_income_to) ? $profile->partner_annual_income_to : 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <aside class="pd-aside">
                <div class="pd-action-card">
                    <div class="pd-action-profile-thumb">
                        <img class="pd-thumb-image" src="{{ $profile->profile_photo_url }}" alt="{{ filled($profile->full_name) ? $profile->full_name : 'Not provided' }}">
                        <div class="pd-action-name">
                            <strong>{{ filled($profile->full_name) ? $profile->full_name : 'Not provided' }}</strong>
                            <span>{{ $profile->age_years !== null ? $profile->age_years . ' yrs' : 'Age not provided' }} · {{ filled($profile->city_living_in) ? $profile->city_living_in : 'Not provided' }}</span>
                        </div>
                    </div>
                    <div class="pd-action-btns">
                        <a href="{{ route('edit-profile') }}" class="pd-btn-interest"><i data-lucide="pencil" width="17" height="17"></i> Edit Profile</a>
                        <button type="button" class="pd-btn-shortlist" id="galleryActionBtn"><i data-lucide="images" width="17" height="17"></i> View Gallery</button>
                    </div>
                    <div class="pd-action-divider"></div>
                    <ul class="pd-quick-info" role="list">
                        <li><i data-lucide="calendar" width="15" height="15"></i><span>{{ $profile->age_years !== null ? $profile->age_years . ' years old' : 'Age not provided' }}</span></li>
                        <li><i data-lucide="map-pin" width="15" height="15"></i><span>{{ filled($profile->city_living_in) ? $profile->city_living_in : 'Not provided' }}, {{ filled($profile->state_living_in) ? $profile->state_living_in : 'Not provided' }}</span></li>
                        <li><i data-lucide="mail" width="15" height="15"></i><span>{{ filled($profile->email) ? $profile->email : 'Not provided' }}</span></li>
                    </ul>
                </div>
            </aside>
            </div>
    </div>

    <!-- ===================== GALLERY LIGHTBOX ===================== -->
    <div class="pd-gallery-overlay" id="galleryOverlay"
        aria-hidden="true"
        role="dialog"
        aria-modal="true"
        aria-label="Manage profile photos"
        data-upload-url="{{ route('upload-photos') }}"
        data-delete-url="{{ url('gallery-photos/__PHOTO__') }}">

        <div class="pd-gallery-modal">

            <button class="pd-gallery-close"
                id="galleryClose"
                aria-label="Close gallery">
                <i data-lucide="x" width="22" height="22"></i>
            </button>

            <div class="pd-gallery-header">
                <div>
                    <h2>Manage Photos</h2>
                    <p>Add up to five gallery photos or remove photos you no longer want to show.</p>
                </div>
                <label class="pd-gallery-add-btn" for="galleryPhotoInput">
                    <i data-lucide="plus" width="17" height="17"></i> Add Photos
                </label>
                <input id="galleryPhotoInput" type="file" accept="image/jpeg,image/png,image/webp" multiple hidden>
            </div>

            <div class="pd-gallery-grid" id="galleryGrid">

                @forelse($profilegallery as $photo)

                <div class="pd-gallery-item">

                    <img
                        src="{{ \App\Website\Services\ProfilePhotoUrl::get($photo->photo) }}"
                        alt="Profile Photo"
                        class="pd-gallery-image"
                        loading="lazy">
                    <button type="button" class="pd-gallery-remove" data-photo-id="{{ $photo->id }}" aria-label="Remove this photo">
                        <i data-lucide="trash-2" width="16" height="16"></i>
                    </button>

                </div>

                @empty

                <div class="pd-gallery-empty">

                    <i data-lucide="image-off" width="48" height="48"></i>

                    <h5>No Photos Available</h5>

                    <p>This member hasn't uploaded any gallery photos yet.</p>

                </div>

                @endforelse

            </div>

        </div>

    </div>

    <!-- Toast notification -->
    <div class="pd-toast" id="pdToast" role="alert" aria-live="polite"></div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/profile-detail.js') }}"></script>
@endsection
