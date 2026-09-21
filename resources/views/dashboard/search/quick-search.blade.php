@extends('layouts.dashboard')

@section('title', 'Quick Search - HimRishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/quick-search.css') }}" />
@endsection

@section('content')
<!-- ===== MAIN ===== -->
<main class="main-content qs-main" id="qs-main">
    <div class="qs-container">

        <!-- Page Header -->
        <div class="qs-page-header">
            <div class="qs-page-header-icon">
                <i data-lucide="search" width="22" height="22"></i>
            </div>
            <div>
                <h1 class="qs-page-title">Quick Search</h1>
                <p class="qs-page-subtitle">Find your match in just a few clicks</p>
            </div>
        </div>

        <!-- Form -->
        <form class="qs-form" id="qsForm" novalidate>

            <!-- Age Range Card -->
            <div class="qs-card">
                <div class="qs-card-head">
                    <span class="qs-card-icon"><i data-lucide="calendar" width="18" height="18"></i></span>
                    <div>
                        <h2 class="qs-card-title">Age Range</h2>
                        <p class="qs-card-desc">Preferred age of partner</p>
                    </div>
                    <div class="qs-age-badge" id="ageBadge">18 – 70 yrs</div>
                </div>
                <div class="qs-card-body">
                    <div class="qs-range-wrap">
                        <div class="qs-range-track-container">
                            <div class="qs-range-track"></div>
                            <div class="qs-range-fill" id="ageFill"></div>
                            <input type="range" class="qs-range" id="ageMin" min="18" max="70" value="18" step="1" aria-label="Minimum age" />
                            <input type="range" class="qs-range" id="ageMax" min="18" max="70" value="70" step="1" aria-label="Maximum age" />
                        </div>
                        <div class="qs-range-endpoints">
                            <span>18</span><span>70</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Religion Card -->
            <div class="qs-card">
                <div class="qs-card-head">
                    <span class="qs-card-icon"><i data-lucide="sun" width="18" height="18"></i></span>
                    <div>
                        <h2 class="qs-card-title">Religion</h2>
                        <p class="qs-card-desc">Select preferred religion</p>
                    </div>
                </div>
                <div class="qs-card-body">
                    <div class="qs-pills-grid" id="religionPills" role="group" aria-label="Religion options">
                        <!-- Rendered by JS -->
                    </div>
                </div>
            </div>

            <!-- Community Card -->
            <div class="qs-card">
                <div class="qs-card-head">
                    <span class="qs-card-icon"><i data-lucide="layers" width="18" height="18"></i></span>
                    <div>
                        <h2 class="qs-card-title">Community / Caste</h2>
                        <p class="qs-card-desc">Select one or more communities</p>
                    </div>
                </div>
                <div class="qs-card-body">
                    <!-- Search Input -->
                    <div class="qs-search-box">
                        <i data-lucide="search" width="16" height="16" class="qs-search-icon"></i>
                        <input type="text" id="communitySearch" class="qs-search-input" placeholder="Search community..." autocomplete="off" aria-label="Search communities" />
                        <button type="button" class="qs-search-clear" id="commSearchClear" aria-label="Clear search" style="display:none;">
                            <i data-lucide="x" width="14" height="14"></i>
                        </button>
                    </div>
                    <!-- Pills grid with scroll -->
                    <div class="qs-pills-scroll" id="communityPills" role="group" aria-label="Community options">
                        <!-- Rendered by JS -->
                    </div>
                    <div class="qs-selected-bar" id="commSelectedBar" style="display:none;">
                        <span class="qs-selected-label">Selected:</span>
                        <div class="qs-selected-chips" id="commSelectedChips"></div>
                        <button type="button" class="qs-clear-link" id="commClearAll">Clear all</button>
                    </div>
                </div>
            </div>

            <!-- Marital Status Card -->
            <div class="qs-card">
                <div class="qs-card-head">
                    <span class="qs-card-icon"><i data-lucide="heart" width="18" height="18"></i></span>
                    <div>
                        <h2 class="qs-card-title">Marital Status</h2>
                        <p class="qs-card-desc">Previous relationship status</p>
                    </div>
                </div>
                <div class="qs-card-body">
                    <div class="qs-pills-grid" id="maritalPills" role="group" aria-label="Marital status options">
                        <!-- Rendered by JS -->
                    </div>
                </div>
            </div>

        </form>
    </div>

    <!-- ===== STICKY BOTTOM BAR ===== -->
    <div class="qs-bottom-bar">
        <div class="qs-bottom-inner">
            <div class="qs-bottom-summary" id="qsSummary">
                <i data-lucide="filter" width="16" height="16"></i>
                <span id="qsSummaryText">No filters applied</span>
            </div>
            <button type="button" class="qs-reset-btn" id="qsResetBtn" aria-label="Reset all filters">
                <i data-lucide="rotate-ccw" width="15" height="15"></i>
                Reset
            </button>
            <button type="button" class="qs-search-btn" id="qsSearchBtn">
                <i data-lucide="search" width="18" height="18"></i>
                Search Profiles
            </button>
        </div>
    </div>

</main>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/quick-search.js') }}?v=20260827-2"></script>

<script>
    window.searchResultsUrl = @json(route('search-results'));
</script>
@endsection
