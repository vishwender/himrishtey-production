@extends('layouts.dashboard')

@section('title', 'Search Results - HimRishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/search-results.css') }}?v=20260827-2" />
@endsection

@section('content')
<!-- MAIN -->
<main class="main-content" id="sr-main">

    <!-- ── STICKY TOP BAR: breadcrumb + active filters ── -->
    <div class="sr-topbar container-xxl">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb-custom">
                <li><a href="{{route('home')}}"><i data-lucide="home" width="14" height="14"></i></a></li>
                <li><a href="{{ $searchReturnUrl }}">{{ $searchSource === 'advanced' ? 'Advanced Search' : 'Quick Search' }}</a></li>
                <li aria-current="page">Search Results</li>
            </ol>
        </nav>

        <!-- Active filter chips (horizontal scroll) -->
        <div class="sr-filters-row" id="srFilterChips" role="list" aria-label="Active filters">
            <!-- Populated by JS from URL params / mock data -->
        </div>

        <!-- Result count + sort -->
        <div class="sr-meta-row">
            <span class="sr-count" id="srCount">
                <i data-lucide="users" width="14" height="14"></i>
                <span id="srCountNum">—</span> profiles found
            </span>
            <div class="sr-sort-wrap">
                <label for="srSort" class="sr-only">Sort by</label>
                <select id="srSort" class="sr-sort-select" aria-label="Sort results">
                    <option value="relevance">Relevance</option>
                    <option value="newest">Newest First</option>
                    <option value="age_asc">Age: Low to High</option>
                    <option value="age_desc">Age: High to Low</option>
                </select>
                <i data-lucide="chevron-down" width="14" height="14" class="sr-sort-arrow"></i>
            </div>
        </div>
    </div>

    <!-- ── GRID AREA ── -->
    <div class="sr-grid-wrap container-xxl" id="srGridWrap">

        <!-- SKELETON (shown on initial load) -->
        <div class="sr-grid" id="srSkeletonGrid" aria-hidden="true">
            <!-- 8 skeleton cards -->
            <div class="sr-skeleton-card">
                <div class="sr-skel-img"></div>
                <div class="sr-skel-body">
                    <div class="sr-skel-line w60"></div>
                    <div class="sr-skel-line w40"></div>
                    <div class="sr-skel-line w80"></div>
                </div>
            </div>
            <div class="sr-skeleton-card">
                <div class="sr-skel-img"></div>
                <div class="sr-skel-body">
                    <div class="sr-skel-line w60"></div>
                    <div class="sr-skel-line w40"></div>
                    <div class="sr-skel-line w80"></div>
                </div>
            </div>
            <div class="sr-skeleton-card">
                <div class="sr-skel-img"></div>
                <div class="sr-skel-body">
                    <div class="sr-skel-line w60"></div>
                    <div class="sr-skel-line w40"></div>
                    <div class="sr-skel-line w80"></div>
                </div>
            </div>
            <div class="sr-skeleton-card">
                <div class="sr-skel-img"></div>
                <div class="sr-skel-body">
                    <div class="sr-skel-line w60"></div>
                    <div class="sr-skel-line w40"></div>
                    <div class="sr-skel-line w80"></div>
                </div>
            </div>
            <div class="sr-skeleton-card">
                <div class="sr-skel-img"></div>
                <div class="sr-skel-body">
                    <div class="sr-skel-line w60"></div>
                    <div class="sr-skel-line w40"></div>
                    <div class="sr-skel-line w80"></div>
                </div>
            </div>
            <div class="sr-skeleton-card">
                <div class="sr-skel-img"></div>
                <div class="sr-skel-body">
                    <div class="sr-skel-line w60"></div>
                    <div class="sr-skel-line w40"></div>
                    <div class="sr-skel-line w80"></div>
                </div>
            </div>
            <div class="sr-skeleton-card">
                <div class="sr-skel-img"></div>
                <div class="sr-skel-body">
                    <div class="sr-skel-line w60"></div>
                    <div class="sr-skel-line w40"></div>
                    <div class="sr-skel-line w80"></div>
                </div>
            </div>
            <div class="sr-skeleton-card">
                <div class="sr-skel-img"></div>
                <div class="sr-skel-body">
                    <div class="sr-skel-line w60"></div>
                    <div class="sr-skel-line w40"></div>
                    <div class="sr-skel-line w80"></div>
                </div>
            </div>
        </div>

        <!-- RESULTS GRID (populated by JS) -->
        <div class="sr-grid" id="srResultGrid" style="display:none;" role="list" aria-label="Search results"></div>

        <!-- EMPTY STATE -->
        <div class="sr-empty" id="srEmpty" style="display:none;">
            <div class="sr-empty-icon" aria-hidden="true">
                <i data-lucide="search-x" width="36" height="36"></i>
            </div>
            <h2 class="sr-empty-title">No profiles found</h2>
            <p class="sr-empty-desc">Try adjusting your search filters to find more matches.</p>
            <a href="{{ $searchReturnUrl }}" class="sr-empty-btn">
                <i data-lucide="sliders-horizontal" width="15" height="15"></i>
                Modify Search
            </a>
        </div>

    </div>

    <!-- ── INFINITE SCROLL LOADER ── -->
    <div class="sr-load-more" id="srLoadMore" style="display:none;" aria-live="polite">
        <div class="sr-loader-bar">
            <span></span><span></span><span></span>
        </div>
        <p>Loading more profiles…</p>
    </div>

    <!-- ── END OF RESULTS ── -->
    <p class="sr-end-msg" id="srEndMsg" style="display:none;">
        <i data-lucide="check-circle" width="16" height="16"></i>
        You've seen all results for this search.
        <a href="{{ $searchReturnUrl }}">Refine your search</a>
    </p>

</main>
@endsection

@section('scripts')
<!-- Laravel search result data -->
<script>
    window.searchResults = @json($searchedMembers);
    console.log('Laravel Search Results:', window.searchResults);
    window.quickSearchUrl = @json(route('quick-search'));
    window.searchResultsUrl = @json(route('search-results'));
    window.searchReturnUrl = @json($searchReturnUrl);
</script>
<!-- Page JS -->
<script src="{{ asset('assets/js/search-results.js') }}?v=20260827-2"></script>
@endsection
