/* ================================================================
   QUICK SEARCH PAGE
   ================================================================ */

(function () {

    'use strict';

    /* ================================================================
       DATA
    ================================================================ */

    const DATA = {

        religion: [
            'Hindu',
            'Sikh',
            'Christian',
            'Buddhist',
            'Muslim'
        ],

        maritalStatus: [
            'Never Married',
            'Widow / Widower',
            'Divorcee',
            'Separated',
            'Any'
        ],

        community: [
            'Brahmin',
            'Agarwal',
            'Bhandari',
            'Arora',
            'Aryasamaj',
            'Bahi',
            'Bhatia',
            'Chaudhary - Ghirth',
            'Chaurasia',
            'Chimbbe',
            'Dhiman - Vishwakarma',
            'Gaddi',
            'Garhwali Rajput',
            'Goswami',
            'Gour',
            'Gujjar',
            'Gupta',
            'Jaat',
            'Jogi',
            'Kamboj',
            'Kashyap',
            'Kayasth',
            'Khatri',
            'Koli',
            'Kshatriya',
            'Labana',
            'Lingayat',
            'Lohar',
            'Maratha',
            'Marwari',
            'Mehra',
            'Nai - Barbar',
            'Naidu',
            'Nair',
            'OBC (Barber-Naayee)',
            'Punjabi',
            'Rajput',
            'Rana',
            'Rawat',
            'Reddy',
            'Saini',
            'Scheduled Caste',
            'Sindhi',
            'Sood',
            'Vaishnav',
            'Yadav',
            'Valmiki',
            'Any',
            'Other'
        ]
    };


    /* ================================================================
       STATE
    ================================================================ */

    const state = {

        ageMin: 18,

        ageMax: 70,

        religion: [],

        community: [],

        maritalStatus: []
    };


    /* ================================================================
       AGE RANGE SLIDER
    ================================================================ */

    function initRangeSlider() {

        const minInput = document.getElementById('ageMin');

        const maxInput = document.getElementById('ageMax');

        const fill = document.getElementById('ageFill');

        const badge = document.getElementById('ageBadge');


        if (!minInput || !maxInput) {
            return;
        }


        function updateFill() {

            const min = parseInt(minInput.min);

            const max = parseInt(minInput.max);

            const lo = parseInt(minInput.value);

            const hi = parseInt(maxInput.value);


            const pLo =
                ((lo - min) / (max - min)) * 100;

            const pHi =
                ((hi - min) / (max - min)) * 100;


            if (fill) {

                fill.style.left = pLo + '%';

                fill.style.right =
                    (100 - pHi) + '%';
            }


            if (badge) {

                badge.textContent =
                    lo + ' – ' + hi + ' yrs';
            }


            state.ageMin = lo;

            state.ageMax = hi;


            updateSummary();
        }


        minInput.addEventListener(
            'input',
            function () {

                if (
                    parseInt(minInput.value) >
                    parseInt(maxInput.value) - 1
                ) {

                    minInput.value =
                        parseInt(maxInput.value) - 1;
                }


                updateFill();
            }
        );


        maxInput.addEventListener(
            'input',
            function () {

                if (
                    parseInt(maxInput.value) <
                    parseInt(minInput.value) + 1
                ) {

                    maxInput.value =
                        parseInt(minInput.value) + 1;
                }


                updateFill();
            }
        );


        updateFill();
    }


    /* ================================================================
       SINGLE SELECT PILLS
    ================================================================ */

    function renderSinglePills(
        containerId,
        items,
        stateKey
    ) {

        const container =
            document.getElementById(containerId);


        if (!container) {
            return;
        }


        container.innerHTML = '';


        items.forEach(function (item) {

            const btn =
                document.createElement('button');


            btn.type = 'button';

            btn.className = 'qs-pill';

            btn.setAttribute(
                'aria-pressed',
                'false'
            );


            btn.innerHTML =
                '<span class="qs-pill-check">' +
                '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">' +
                '<polyline points="20 6 9 17 4 12"/>' +
                '</svg>' +
                '</span>' +
                '<span>' +
                item +
                '</span>';


            btn.addEventListener(
                'click',
                function () {

                    if (
                        state[stateKey].includes(item)
                    ) {

                        state[stateKey] = [];

                    } else {

                        state[stateKey] = [item];
                    }


                    refreshSinglePills(
                        container,
                        items,
                        stateKey
                    );


                    updateSummary();
                }
            );


            container.appendChild(btn);
        });
    }


    function refreshSinglePills(
        container,
        items,
        stateKey
    ) {

        if (!container) {
            return;
        }


        const buttons =
            container.querySelectorAll('.qs-pill');


        buttons.forEach(function (btn, index) {

            const isSelected =
                state[stateKey].includes(
                    items[index]
                );


            btn.classList.toggle(
                'selected',
                isSelected
            );


            btn.setAttribute(
                'aria-pressed',
                isSelected
                    ? 'true'
                    : 'false'
            );
        });
    }


    /* ================================================================
       COMMUNITY MULTI SELECT
    ================================================================ */

    function renderCommunityPills() {

        const container =
            document.getElementById(
                'communityPills'
            );


        const searchInput =
            document.getElementById(
                'communitySearch'
            );


        const clearBtn =
            document.getElementById(
                'commSearchClear'
            );


        if (!container) {
            return;
        }


        container.innerHTML = '';


        DATA.community.forEach(
            function (item) {

                const btn =
                    document.createElement(
                        'button'
                    );


                btn.type = 'button';

                btn.className = 'qs-pill';

                btn.dataset.value = item;


                btn.setAttribute(
                    'aria-pressed',
                    'false'
                );


                btn.innerHTML =
                    '<span class="qs-pill-check">' +
                    '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">' +
                    '<polyline points="20 6 9 17 4 12"/>' +
                    '</svg>' +
                    '</span>' +
                    '<span>' +
                    item +
                    '</span>';


                btn.addEventListener(
                    'click',
                    function () {

                        toggleCommunity(item);

                        refreshCommunityPills();

                        refreshCommSelectedBar();

                        updateSummary();
                    }
                );


                container.appendChild(btn);
            }
        );


        /* Search */

        if (searchInput) {

            searchInput.addEventListener(
                'input',
                function () {

                    const q =
                        this.value
                            .trim()
                            .toLowerCase();


                    if (clearBtn) {

                        clearBtn.style.display =
                            q ? 'flex' : 'none';
                    }


                    filterCommunityPills(q);
                }
            );
        }


        /* Clear search */

        if (clearBtn) {

            clearBtn.addEventListener(
                'click',
                function () {

                    searchInput.value = '';

                    clearBtn.style.display =
                        'none';

                    filterCommunityPills('');

                    searchInput.focus();
                }
            );
        }


        /* Clear all */

        const clearAllBtn =
            document.getElementById(
                'commClearAll'
            );


        if (clearAllBtn) {

            clearAllBtn.addEventListener(
                'click',
                function () {

                    state.community = [];

                    refreshCommunityPills();

                    refreshCommSelectedBar();

                    updateSummary();
                }
            );
        }
    }


    function toggleCommunity(item) {

        const index =
            state.community.indexOf(item);


        if (index === -1) {

            state.community.push(item);

        } else {

            state.community.splice(
                index,
                1
            );
        }
    }


    function refreshCommunityPills() {

        const container =
            document.getElementById(
                'communityPills'
            );


        if (!container) {
            return;
        }


        container
            .querySelectorAll('.qs-pill')
            .forEach(function (btn) {

                const value =
                    btn.dataset.value;


                const isSelected =
                    state.community.includes(
                        value
                    );


                btn.classList.toggle(
                    'selected',
                    isSelected
                );


                btn.setAttribute(
                    'aria-pressed',
                    isSelected
                        ? 'true'
                        : 'false'
                );
            });
    }


    function filterCommunityPills(query) {

        const container =
            document.getElementById(
                'communityPills'
            );


        if (!container) {
            return;
        }


        let hasVisible = false;


        container
            .querySelectorAll('.qs-pill')
            .forEach(function (btn) {

                const value =
                    btn.dataset.value
                        .toLowerCase();


                const show =
                    !query ||
                    value.includes(query);


                btn.classList.toggle(
                    'qs-pill-hidden',
                    !show
                );


                if (show) {
                    hasVisible = true;
                }
            });
    }


    function refreshCommSelectedBar() {

        const bar =
            document.getElementById(
                'commSelectedBar'
            );


        const chips =
            document.getElementById(
                'commSelectedChips'
            );


        if (!bar || !chips) {
            return;
        }


        if (state.community.length === 0) {

            bar.style.display = 'none';

            return;
        }


        bar.style.display = 'flex';

        chips.innerHTML = '';


        state.community.forEach(
            function (value) {

                const chip =
                    document.createElement(
                        'span'
                    );


                chip.className =
                    'qs-sel-chip';


                chip.innerHTML =
                    value +
                    '<button type="button" class="qs-sel-chip-remove" aria-label="Remove">' +
                    '<svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">' +
                    '<line x1="18" y1="6" x2="6" y2="18"/>' +
                    '<line x1="6" y1="6" x2="18" y2="18"/>' +
                    '</svg>' +
                    '</button>';


                chip
                    .querySelector(
                        '.qs-sel-chip-remove'
                    )
                    .addEventListener(
                        'click',
                        function () {

                            state.community =
                                state.community.filter(
                                    function (v) {
                                        return v !== value;
                                    }
                                );


                            refreshCommunityPills();

                            refreshCommSelectedBar();

                            updateSummary();
                        }
                    );


                chips.appendChild(chip);
            }
        );
    }


    /* ================================================================
       SUMMARY
    ================================================================ */

    function updateSummary() {

        const el =
            document.getElementById(
                'qsSummaryText'
            );


        if (!el) {
            return;
        }


        const parts = [];


        if (
            state.ageMin !== 18 ||
            state.ageMax !== 70
        ) {

            parts.push(
                'Age: ' +
                state.ageMin +
                '–' +
                state.ageMax
            );
        }


        if (state.religion.length) {

            parts.push(
                'Religion: ' +
                state.religion.join(', ')
            );
        }


        if (state.community.length) {

            parts.push(
                state.community.length +
                ' communit' +
                (
                    state.community.length === 1
                        ? 'y'
                        : 'ies'
                )
            );
        }


        if (state.maritalStatus.length) {

            parts.push(
                'Marital Status: ' +
                state.maritalStatus.join(', ')
            );
        }


        el.textContent =
            parts.length
                ? parts.join(' · ')
                : 'No filters applied';
    }


    /* ================================================================
       SEARCH
    ================================================================ */

    function doSearch() {

        const params =
            new URLSearchParams();

        params.set('_source', 'quick');


        /*
        |--------------------------------------------------------------------------
        | Age
        |--------------------------------------------------------------------------
        */

        if (state.ageMin > 18) {

            params.set(
                'partner_age_from',
                state.ageMin
            );
        }


        if (state.ageMax < 70) {

            params.set(
                'partner_age_to',
                state.ageMax
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Religion
        |--------------------------------------------------------------------------
        */

        if (
            state.religion.length &&
            state.religion[0] !== 'Any'
        ) {

            params.set(
                'partner_religion',
                state.religion.join(',')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Community / Cast
        |--------------------------------------------------------------------------
        */

        if (
            state.community.length &&
            !state.community.includes('Any')
        ) {

            params.set(
                'partner_cast',
                state.community.join(',')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Marital Status
        |--------------------------------------------------------------------------
        */

        if (
            state.maritalStatus.length &&
            state.maritalStatus[0] !== 'Any'
        ) {

            params.set(
                'marital_status',
                state.maritalStatus[0]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Navigate to Laravel route
        |--------------------------------------------------------------------------
        */

        window.location.href =
            window.searchResultsUrl +
            (
                params.toString()
                    ? '?' + params.toString()
                    : ''
            );
    }


    /* ================================================================
       RESET
    ================================================================ */

    function resetAll() {

        state.ageMin = 18;

        state.ageMax = 70;

        state.religion = [];

        state.community = [];

        state.maritalStatus = [];


        const minInput =
            document.getElementById('ageMin');


        const maxInput =
            document.getElementById('ageMax');


        if (minInput) {
            minInput.value = 18;
        }


        if (maxInput) {
            maxInput.value = 70;
        }


        initRangeSlider();


        refreshSinglePills(
            document.getElementById(
                'religionPills'
            ),
            DATA.religion,
            'religion'
        );


        refreshSinglePills(
            document.getElementById(
                'maritalPills'
            ),
            DATA.maritalStatus,
            'maritalStatus'
        );


        refreshCommunityPills();

        refreshCommSelectedBar();


        const searchInput =
            document.getElementById(
                'communitySearch'
            );


        const clearBtn =
            document.getElementById(
                'commSearchClear'
            );


        if (searchInput) {

            searchInput.value = '';
        }


        if (clearBtn) {

            clearBtn.style.display = 'none';
        }


        filterCommunityPills('');


        updateSummary();
    }


    /* ================================================================
       INIT
    ================================================================ */

    function init() {

        initRangeSlider();


        renderSinglePills(
            'religionPills',
            DATA.religion,
            'religion'
        );


        renderCommunityPills();


        renderSinglePills(
            'maritalPills',
            DATA.maritalStatus,
            'maritalStatus'
        );


        updateSummary();


        const searchBtn =
            document.getElementById(
                'qsSearchBtn'
            );


        const resetBtn =
            document.getElementById(
                'qsResetBtn'
            );


        if (searchBtn) {

            searchBtn.addEventListener(
                'click',
                doSearch
            );
        }


        if (resetBtn) {

            resetBtn.addEventListener(
                'click',
                resetAll
            );
        }


        if (window.lucide) {

            lucide.createIcons();
        }
    }


    /* ================================================================
       DOM READY
    ================================================================ */

    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            init
        );

    } else {

        init();
    }

})();
