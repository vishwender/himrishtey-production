@extends('layouts.dashboard')

@section('title','Success Stories - Himrishtey')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/css/success-stories.css')}}" />
@endsection

@section('content')
<!-- ========== MAIN ========== -->
<main class="ss-main" id="main-content">

    <!-- Page Header -->
    <div class="ss-page-header">
        <div class="ss-page-header-left">
            <h1 class="ss-page-title">
                <i data-lucide="heart-handshake" width="26" height="26" class="ss-title-icon" aria-hidden="true"></i>
                Success Stories
            </h1>
            <p class="ss-page-subtitle">Real couples, real happiness — from HimRishtey</p>
        </div>
        <button class="ss-add-btn" id="ssAddStoryBtn" aria-label="Add your success story">
            <i data-lucide="plus-circle" width="18" height="18"></i>
            Add Story
        </button>
    </div>

    <!-- Privacy Info Banner -->
    <div class="ss-privacy-banner" role="note" aria-label="Privacy notice">
        <span class="ss-privacy-icon" aria-hidden="true">
            <i data-lucide="shield-check" width="18" height="18"></i>
        </span>
        <p class="ss-privacy-text">
            We don't post all success stories here. Your privacy is our top priority.
            You can send us your story by clicking <strong>Add Story</strong>.
        </p>
    </div>

    <!-- Skeleton Loader -->
    <div class="ss-skeleton-grid" id="ssSkeleton" aria-hidden="true">
        <div class="ss-skeleton-card skeleton"></div>
        <div class="ss-skeleton-card skeleton"></div>
        <div class="ss-skeleton-card skeleton"></div>
        <div class="ss-skeleton-card skeleton"></div>
    </div>

    <!-- Stories Grid -->
    <div class="ss-grid" id="ssGrid" hidden aria-label="Success stories list" role="list">
        <!-- Populated by JS -->
    </div>

    <!-- Empty State -->
    <div class="ss-empty" id="ssEmpty" hidden>
        <div class="ss-empty-illustration" aria-hidden="true">
            <i data-lucide="heart-handshake" width="56" height="56"></i>
        </div>
        <h2 class="ss-empty-title">Be the first to share!</h2>
        <p class="ss-empty-desc">No stories have been published yet. Found your match on HimRishtey? We'd love to celebrate with you.</p>
        <button class="ss-empty-cta" id="ssEmptyAddBtn">
            <i data-lucide="plus-circle" width="16" height="16"></i>
            Share Your Story
        </button>
    </div>

</main>

<!-- ========== ADD STORY MODAL ========== -->
<div class="ss-modal-overlay" id="ssModalOverlay" role="dialog" aria-modal="true" aria-labelledby="ssModalTitle" hidden>
    <div class="ss-modal" id="ssModal">

        <div class="ss-modal-header">
            <h2 class="ss-modal-title" id="ssModalTitle">
                <i data-lucide="heart" width="18" height="18"></i>
                Add Your Story
            </h2>
            <button class="ss-modal-close" id="ssModalClose" aria-label="Close dialog">
                <i data-lucide="x" width="18" height="18"></i>
            </button>
        </div>

        <form class="ss-modal-form" id="ssStoryForm" novalidate>
            <div class="ss-modal-body">

                <!-- Image Upload -->
                <div class="ss-upload-zone" id="ssUploadZone" role="button" tabindex="0" aria-label="Upload wedding photo">
                    <input type="file" id="ssPhotoInput" accept="image/*" class="ss-file-input" aria-label="Choose photo" />
                    <div class="ss-upload-placeholder" id="ssUploadPlaceholder">
                        <i data-lucide="image-plus" width="32" height="32"></i>
                        <span>Upload your wedding photo</span>
                        <small>JPG, PNG · Max 5MB</small>
                    </div>
                    <img class="ss-upload-preview" id="ssUploadPreview" src="" alt="Preview of your wedding photo" hidden />
                    <button type="button" class="ss-upload-remove" id="ssUploadRemove" aria-label="Remove photo" hidden>
                        <i data-lucide="x-circle" width="20" height="20"></i>
                    </button>
                </div>

                <!-- Fields -->
                <div class="ss-field-row">
                    <div class="ss-field-group">
                        <label class="ss-label" for="ssGroomName">Groom's Name <span class="ss-required" aria-hidden="true">*</span></label>
                        <input class="ss-input" type="text" id="ssGroomName" name="groom_name" placeholder="e.g. Rahul Sharma" required autocomplete="off" />
                    </div>
                    <div class="ss-field-group">
                        <label class="ss-label" for="ssBrideName">Bride's Name <span class="ss-required" aria-hidden="true">*</span></label>
                        <input class="ss-input" type="text" id="ssBrideName" name="bride_name" placeholder="e.g. Priya Verma" required autocomplete="off" />
                    </div>
                </div>

                <div class="ss-field-group">
                    <label class="ss-label" for="ssStoryText">Write Your Story <span class="ss-required" aria-hidden="true">*</span></label>
                    <textarea class="ss-textarea" id="ssStoryText" name="description" rows="4" placeholder="Share how you found each other on HimRishtey..." required></textarea>
                    <span class="ss-char-count" id="ssCharCount" aria-live="polite">0 / 500</span>
                </div>

                <p class="ss-form-note">
                    <i data-lucide="info" width="14" height="14"></i>
                    Your story will be reviewed before publishing. We respect your privacy.
                </p>

                <!-- Inline errors -->
                <p class="ss-form-error" id="ssFormError" role="alert" hidden>Please fill in all required fields.</p>
            </div>

            <div class="ss-modal-footer">
                <button type="button" class="ss-btn-ghost" id="ssModalCancel">Cancel</button>
                <button type="submit" class="ss-btn-primary" id="ssSubmitBtn">
                    <span class="ss-btn-label">
                        <i data-lucide="send" width="16" height="16"></i>
                        Post Story
                    </span>
                    <span class="ss-btn-loader" hidden>
                        <i data-lucide="loader-2" width="16" height="16" class="ss-spin"></i>
                        Posting…
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========== STORY LIGHTBOX ========== -->
<div class="ss-lightbox" id="ssLightbox" role="dialog" aria-modal="true" aria-label="Story detail" hidden>
    <div class="ss-lightbox-backdrop" id="ssLightboxBackdrop"></div>
    <div class="ss-lightbox-card">
        <button class="ss-lightbox-close" id="ssLightboxClose" aria-label="Close story">
            <i data-lucide="x" width="20" height="20"></i>
        </button>
        <div class="ss-lightbox-img-wrap">
            <img class="ss-lightbox-img" id="ssLightboxImg" src="" alt="" loading="lazy" />
        </div>
        <div class="ss-lightbox-body">
            <h3 class="ss-lightbox-couple" id="ssLightboxCouple"></h3>
            <p class="ss-lightbox-detail" id="ssLightboxDetail"></p>
        </div>
    </div>
</div>

<!-- ========== TOAST ========== -->
<div class="ss-toast" id="ssToast" role="status" aria-live="polite">
    <i data-lucide="check-circle-2" width="18" height="18"></i>
    <span id="ssToastMsg"></span>
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/js/success-stories.js')}}"></script>
@endsection