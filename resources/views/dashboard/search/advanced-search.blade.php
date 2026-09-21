@extends('layouts.dashboard')

@section('title', 'Advance Search - HimRishtey')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/advanced-search.css') }}" />
@endsection

@section('content')
<!-- ===== MAIN CONTENT ===== -->
<main class="main-content" id="main-content">
    <div class="container-xxl">

        <!-- Page Header -->
        <div class="page-header-bar">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb-custom">
                        <li><a href="{{route('home')}}">Home</a></li>
                        <li>Advance Search</li>
                    </ol>
                </nav>
                <h1 style="font-family:var(--font-display);font-size:var(--text-xl);font-weight:700;color:var(--color-text);margin-top:var(--space-1);">
                    Advance Search
                </h1>
                <p style="font-size:var(--text-sm);color:var(--color-text-muted);margin-top:2px;max-width:55ch;">
                    Fill in only the fields you want to filter. All fields are optional.
                </p>
            </div>
        </div>

        <!-- Two-column layout -->
        <div class="adv-search-layout">

            <!-- ===== LEFT: FORM ===== -->
            <div>

                <!-- ---- Basic Info ---- -->
                <div class="adv-card" style="margin-bottom: var(--space-5);">
                    <div class="adv-card-header">
                        <div class="adv-card-header-icon"><i data-lucide="user-search" width="20" height="20"></i></div>
                        <div>
                            <h2 class="adv-card-title">Basic Details</h2>
                            <p class="adv-card-subtitle">Search by ID or age range</p>
                        </div>
                    </div>
                    <div class="adv-card-body">
                        <p class="adv-hint"><i data-lucide="info" width="12" height="12"></i> All fields are optional — fill only what you want to filter.</p>

                        <!-- Profile ID -->
                        <div class="adv-field">
                            <label class="adv-label" for="profileId">
                                <i data-lucide="id-card" width="15" height="15"></i> Profile ID
                            </label>
                            <input type="text" id="profileId" class="adv-input" placeholder="e.g. HIM10234" autocomplete="off" />
                        </div>

                        <!-- Age Range -->
                        <div class="adv-field">
                            <div class="adv-label"><i data-lucide="calendar" width="15" height="15"></i> Age Range</div>
                            <div class="adv-range-wrap">
                                <div class="adv-range-labels">
                                    <div class="adv-range-val"><span>Min</span> <span id="ageMinDisplay">18</span> yrs</div>
                                    <div class="adv-range-val" style="text-align:right;"><span>Max</span> <span id="ageMaxDisplay">70</span> yrs</div>
                                </div>
                                <div class="range-track-container" id="ageTrackContainer">
                                    <div class="range-track"></div>
                                    <div class="range-fill" id="ageFill"></div>
                                    <input type="range" class="adv-range" id="ageMin" min="18" max="70" value="18" step="1" aria-label="Minimum age" />
                                    <input type="range" class="adv-range" id="ageMax" min="18" max="70" value="70" step="1" aria-label="Maximum age" />
                                </div>
                            </div>
                        </div>

                        <!-- Height Range -->
                        <div class="adv-field">
                            <div class="adv-label"><i data-lucide="ruler" width="15" height="15"></i> Height Range (ft)</div>
                            <div class="adv-range-wrap">
                                <div class="adv-range-labels">
                                    <div class="adv-range-val"><span>Min</span> <span id="htMinDisplay">4.6</span> ft</div>
                                    <div class="adv-range-val" style="text-align:right;"><span>Max</span> <span id="htMaxDisplay">7.0</span> ft</div>
                                </div>
                                <div class="range-track-container">
                                    <div class="range-track"></div>
                                    <div class="range-fill" id="htFill"></div>
                                    <input type="range" class="adv-range" id="htMin" min="46" max="70" value="46" step="1" aria-label="Minimum height" />
                                    <input type="range" class="adv-range" id="htMax" min="46" max="70" value="70" step="1" aria-label="Maximum height" />
                                </div>
                            </div>
                        </div>

                        <!-- Annual Income -->
                        <div class="adv-field">
                            <div class="adv-label"><i data-lucide="indian-rupee" width="15" height="15"></i> Annual Income (LPA)</div>
                            <div class="adv-range-wrap">
                                <div class="adv-range-labels">
                                    <div class="adv-range-val"><span>Min</span> ₹<span id="incMinDisplay">0</span>L</div>
                                    <div class="adv-range-val" style="text-align:right;"><span>Max</span> ₹<span id="incMaxDisplay">50</span>L</div>
                                </div>
                                <div class="range-track-container">
                                    <div class="range-track"></div>
                                    <div class="range-fill" id="incFill"></div>
                                    <input type="range" class="adv-range" id="incMin" min="0" max="50" value="0" step="1" aria-label="Minimum income" />
                                    <input type="range" class="adv-range" id="incMax" min="0" max="50" value="50" step="1" aria-label="Maximum income" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ---- Religion & Community ---- -->
                <div class="adv-card" style="margin-bottom: var(--space-5);">
                    <div class="adv-card-header">
                        <div class="adv-card-header-icon"><i data-lucide="users" width="20" height="20"></i></div>
                        <div>
                            <h2 class="adv-card-title">Religion & Community</h2>
                            <p class="adv-card-subtitle">Caste, religion and language filters</p>
                        </div>
                    </div>
                    <div class="adv-card-body">
                        <div class="adv-two-col">

                            <!-- Religion -->
                            <div class="adv-field">
                                <div class="adv-label"><i data-lucide="sun" width="15" height="15"></i> Religion</div>
                                <div class="adv-dropdown-wrap" id="wrap-religion">
                                    <button class="adv-select-trigger" id="trigger-religion" aria-haspopup="listbox" aria-expanded="false" type="button">
                                        <div class="chips-wrap" id="chips-religion"><span class="adv-placeholder-txt">Select religion</span></div>
                                        <i data-lucide="chevron-down" width="16" height="16" style="flex-shrink:0;color:var(--color-text-faint);"></i>
                                    </button>
                                    <div class="adv-dropdown" id="dd-religion" role="listbox" aria-multiselectable="true" aria-label="Religion options">
                                        <div class="adv-dropdown-list" id="list-religion"></div>
                                        <div class="adv-dropdown-footer">
                                            <button class="adv-dropdown-clear" onclick="clearField('religion')">Clear</button>
                                            <button class="adv-dropdown-done" onclick="closeDd('religion')">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Community -->
                            <div class="adv-field">
                                <div class="adv-label"><i data-lucide="layers" width="15" height="15"></i> Community / Caste</div>
                                <div class="adv-dropdown-wrap" id="wrap-community">
                                    <button class="adv-select-trigger" id="trigger-community" aria-haspopup="listbox" aria-expanded="false" type="button">
                                        <div class="chips-wrap" id="chips-community"><span class="adv-placeholder-txt">Select community</span></div>
                                        <i data-lucide="chevron-down" width="16" height="16" style="flex-shrink:0;color:var(--color-text-faint);"></i>
                                    </button>
                                    <div class="adv-dropdown" id="dd-community" role="listbox" aria-multiselectable="true" aria-label="Community options">
                                        <div class="adv-dropdown-search">
                                            <i data-lucide="search" width="14" height="14" class="adv-dropdown-search-icon"></i>
                                            <input type="text" placeholder="Search community..." id="search-community" oninput="filterOptions('community', this.value)" aria-label="Search communities" />
                                        </div>
                                        <div class="adv-dropdown-list" id="list-community"></div>
                                        <div class="adv-dropdown-footer">
                                            <button class="adv-dropdown-clear" onclick="clearField('community')">Clear</button>
                                            <button class="adv-dropdown-done" onclick="closeDd('community')">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mother Tongue -->
                            <div class="adv-field">
                                <div class="adv-label"><i data-lucide="message-square" width="15" height="15"></i> Mother Tongue</div>
                                <div class="adv-dropdown-wrap" id="wrap-tongue">
                                    <button class="adv-select-trigger" id="trigger-tongue" aria-haspopup="listbox" aria-expanded="false" type="button">
                                        <div class="chips-wrap" id="chips-tongue"><span class="adv-placeholder-txt">Select tongue</span></div>
                                        <i data-lucide="chevron-down" width="16" height="16" style="flex-shrink:0;color:var(--color-text-faint);"></i>
                                    </button>
                                    <div class="adv-dropdown" id="dd-tongue" role="listbox" aria-multiselectable="true" aria-label="Mother tongue options">
                                        <div class="adv-dropdown-list" id="list-tongue"></div>
                                        <div class="adv-dropdown-footer">
                                            <button class="adv-dropdown-clear" onclick="clearField('tongue')">Clear</button>
                                            <button class="adv-dropdown-done" onclick="closeDd('tongue')">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- State -->
                            <div class="adv-field">
                                <div class="adv-label"><i data-lucide="map-pin" width="15" height="15"></i> State</div>
                                <div class="adv-dropdown-wrap" id="wrap-state">
                                    <button class="adv-select-trigger" id="trigger-state" aria-haspopup="listbox" aria-expanded="false" type="button">
                                        <div class="chips-wrap" id="chips-state"><span class="adv-placeholder-txt">Select state</span></div>
                                        <i data-lucide="chevron-down" width="16" height="16" style="flex-shrink:0;color:var(--color-text-faint);"></i>
                                    </button>
                                    <div class="adv-dropdown" id="dd-state" role="listbox" aria-multiselectable="true" aria-label="State options">
                                        <div class="adv-dropdown-search">
                                            <i data-lucide="search" width="14" height="14" class="adv-dropdown-search-icon"></i>
                                            <input type="text" placeholder="Search state..." id="search-state" oninput="filterOptions('state', this.value)" aria-label="Search states" />
                                        </div>
                                        <div class="adv-dropdown-list" id="list-state"></div>
                                        <div class="adv-dropdown-footer">
                                            <button class="adv-dropdown-clear" onclick="clearField('state')">Clear</button>
                                            <button class="adv-dropdown-done" onclick="closeDd('state')">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manglik -->
                        <div class="adv-field">
                            <div class="adv-label"><i data-lucide="star" width="15" height="15"></i> Manglik</div>
                            <div class="adv-pills" role="group" aria-label="Manglik preference">
                                <label class="adv-pill-label"><input type="radio" name="manglik" value="" checked /><span class="adv-pill">Any</span></label>
                                <label class="adv-pill-label"><input type="radio" name="manglik" value="Yes" /><span class="adv-pill">Yes</span></label>
                                <label class="adv-pill-label"><input type="radio" name="manglik" value="No" /><span class="adv-pill">No</span></label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ---- Education & Career ---- -->
                <div class="adv-card" style="margin-bottom: var(--space-5);">
                    <div class="adv-card-header">
                        <div class="adv-card-header-icon"><i data-lucide="graduation-cap" width="20" height="20"></i></div>
                        <div>
                            <h2 class="adv-card-title">Education & Career</h2>
                            <p class="adv-card-subtitle">Qualification and employment filters</p>
                        </div>
                    </div>
                    <div class="adv-card-body">
                        <div class="adv-two-col">

                            <!-- Education -->
                            <div class="adv-field">
                                <div class="adv-label"><i data-lucide="book-open" width="15" height="15"></i> Education</div>
                                <div class="adv-dropdown-wrap" id="wrap-education">
                                    <button class="adv-select-trigger" id="trigger-education" aria-haspopup="listbox" aria-expanded="false" type="button">
                                        <div class="chips-wrap" id="chips-education"><span class="adv-placeholder-txt">Select education</span></div>
                                        <i data-lucide="chevron-down" width="16" height="16" style="flex-shrink:0;color:var(--color-text-faint);"></i>
                                    </button>
                                    <div class="adv-dropdown" id="dd-education" role="listbox" aria-multiselectable="true" aria-label="Education options">
                                        <div class="adv-dropdown-search">
                                            <i data-lucide="search" width="14" height="14" class="adv-dropdown-search-icon"></i>
                                            <input type="text" placeholder="Search qualification..." id="search-education" oninput="filterOptions('education', this.value)" aria-label="Search qualifications" />
                                        </div>
                                        <div class="adv-dropdown-list" id="list-education"></div>
                                        <div class="adv-dropdown-footer">
                                            <button class="adv-dropdown-clear" onclick="clearField('education')">Clear</button>
                                            <button class="adv-dropdown-done" onclick="closeDd('education')">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Employed In -->
                            <div class="adv-field">
                                <div class="adv-label"><i data-lucide="briefcase" width="15" height="15"></i> Employed In</div>
                                <div class="adv-dropdown-wrap" id="wrap-employed">
                                    <button class="adv-select-trigger" id="trigger-employed" aria-haspopup="listbox" aria-expanded="false" type="button">
                                        <div class="chips-wrap" id="chips-employed"><span class="adv-placeholder-txt">Select employment</span></div>
                                        <i data-lucide="chevron-down" width="16" height="16" style="flex-shrink:0;color:var(--color-text-faint);"></i>
                                    </button>
                                    <div class="adv-dropdown" id="dd-employed" role="listbox" aria-multiselectable="true" aria-label="Employment options">
                                        <div class="adv-dropdown-list" id="list-employed"></div>
                                        <div class="adv-dropdown-footer">
                                            <button class="adv-dropdown-clear" onclick="clearField('employed')">Clear</button>
                                            <button class="adv-dropdown-done" onclick="closeDd('employed')">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ---- Marital Status ---- -->
                <div class="adv-card" style="margin-bottom: var(--space-5);">
                    <div class="adv-card-header">
                        <div class="adv-card-header-icon"><i data-lucide="heart" width="20" height="20"></i></div>
                        <div>
                            <h2 class="adv-card-title">Marital Status</h2>
                            <p class="adv-card-subtitle">Previous relationship status</p>
                        </div>
                    </div>
                    <div class="adv-card-body">
                        <div class="adv-pills" role="group" aria-label="Marital status">
                            <label class="adv-pill-label"><input type="radio" name="maritalStatus" value="" checked /><span class="adv-pill">Any</span></label>
                            <label class="adv-pill-label"><input type="radio" name="maritalStatus" value="Never married" /><span class="adv-pill">Never Married</span></label>
                            <label class="adv-pill-label"><input type="radio" name="maritalStatus" value="Widow" /><span class="adv-pill">Widow/er</span></label>
                            <label class="adv-pill-label"><input type="radio" name="maritalStatus" value="Divorcee" /><span class="adv-pill">Divorcee</span></label>
                            <label class="adv-pill-label"><input type="radio" name="maritalStatus" value="Separated" /><span class="adv-pill">Separated</span></label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ===== RIGHT: SUMMARY + SEARCH ===== -->
            <aside class="adv-search-sticky" aria-label="Search summary">
                <div class="adv-summary-card">
                    <div class="adv-summary-header">
                        <h2 class="adv-summary-title">Your Filters</h2>
                        <p class="adv-summary-subtitle">Review before searching</p>
                    </div>
                    <div class="adv-summary-body">
                        <div class="adv-filter-chip-row" id="activeFiltersRow" aria-live="polite" aria-label="Active filters">
                            <span class="adv-empty-filters" id="noFiltersMsg">No filters applied yet</span>
                        </div>
                        <button class="adv-search-btn" onclick="doSearch()" aria-label="Search profiles">
                            <i data-lucide="search" width="18" height="18"></i> Search Profiles
                        </button>
                        <button class="adv-reset-btn" onclick="resetAll()" aria-label="Reset all filters">
                            <i data-lucide="refresh-ccw" width="16" height="16"></i> Reset All
                        </button>
                    </div>
                </div>

                <!-- Tips card -->
                <div style="margin-top: var(--space-4); background: var(--color-primary-light); border-radius: var(--radius-xl); padding: var(--space-5); border: 1px solid rgba(217,39,104,0.15);">
                    <div style="font-family:var(--font-display);font-size:var(--text-base);font-weight:700;color:var(--color-primary);margin-bottom:var(--space-2);">💡 Search Tips</div>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:var(--space-2);">
                        <li style="font-size:var(--text-xs);color:var(--color-text-medium);display:flex;gap:6px;align-items:flex-start;"><span style="color:var(--color-primary);font-weight:700;flex-shrink:0;">→</span>Leave fields blank to broaden results</li>
                        <li style="font-size:var(--text-xs);color:var(--color-text-medium);display:flex;gap:6px;align-items:flex-start;"><span style="color:var(--color-primary);font-weight:700;flex-shrink:0;">→</span>Multi-select dropdowns let you pick multiple values</li>
                        <li style="font-size:var(--text-xs);color:var(--color-text-medium);display:flex;gap:6px;align-items:flex-start;"><span style="color:var(--color-primary);font-weight:700;flex-shrink:0;">→</span>Use Profile ID for a direct lookup</li>
                    </ul>
                </div>
            </aside>
        </div>

    </div>
</main>
@endsection

<!-- ===== MOBILE BOTTOM BAR ===== -->
<div class="adv-bottom-bar" aria-label="Search actions (mobile)">
    <button class="adv-bottom-reset" onclick="resetAll()" aria-label="Reset all filters">
        <i data-lucide="refresh-ccw" width="15" height="15"></i> Reset
    </button>
    <button class="adv-bottom-search" onclick="doSearch()" aria-label="Search profiles">
        <i data-lucide="search" width="16" height="16"></i> Search Profiles
    </button>
</div>

<!-- Dropdown backdrop -->
<div class="adv-backdrop" id="advBackdrop"></div>

@section('scripts')
<script>
    window.searchResultsUrl = @json(route('search-results'));
</script>
<script src="{{asset('assets/js/advanced-search.js') }}?v=20260827-2"></script>
@endsection

</body>

</html>
