/* ================================================================
   SEARCH BY PROFILE ID — search-by-id.js
   Laravel endpoint:
   GET /search-by-profile-id?profile_id=HIM10027
   ================================================================ */

(function () {
    'use strict';

    /* ================================================================
       DOM REFERENCES
    ================================================================ */

    const input = document.getElementById('profileIdInput');
    const clearInputBtn = document.getElementById('sbidClearInput');
    const searchBtn = document.getElementById('sbidSearchBtn');

    const btnText = searchBtn
        ? searchBtn.querySelector('.sbid-btn-text')
        : null;

    const btnIcon = searchBtn
        ? searchBtn.querySelector('.sbid-btn-icon')
        : null;

    const btnSpinner = searchBtn
        ? searchBtn.querySelector('.sbid-btn-spinner')
        : null;

    const tryAgainBtn = document.getElementById('sbidTryAgain');

    /* States */
    const stateIdle = document.getElementById('stateIdle');
    const stateLoading = document.getElementById('stateLoading');
    const stateNotFound = document.getElementById('stateNotFound');
    const stateResult = document.getElementById('stateResult');


    /* ================================================================
       STATE MANAGEMENT
    ================================================================ */

    function showState(state) {
        const states = [
            stateIdle,
            stateLoading,
            stateNotFound,
            stateResult
        ];

        states.forEach(function (element) {
            if (element) {
                element.style.display = 'none';
            }
        });

        if (!state) {
            return;
        }

        state.style.display = '';

        if (state === stateResult) {
            state.classList.remove('visible');

            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    state.classList.add('visible');
                });
            });
        }
    }


    /* ================================================================
       SEARCH BUTTON LOADING STATE
    ================================================================ */

    function setSearching(isSearching) {
        if (!searchBtn) {
            return;
        }

        searchBtn.disabled = isSearching;

        if (btnText) {
            btnText.style.display = isSearching ? 'none' : '';
        }

        if (btnIcon) {
            btnIcon.style.display = isSearching ? 'none' : '';
        }

        if (btnSpinner) {
            btnSpinner.style.display = isSearching ? '' : 'none';
        }
    }


    /* ================================================================
       INPUT ERROR
    ================================================================ */

    function shakeInput() {
        if (!input) {
            return;
        }

        input.classList.add('sbid-input-error', 'shake');

        input.addEventListener(
            'animationend',
            function () {
                input.classList.remove('shake');
            },
            { once: true }
        );
    }


    /* ================================================================
       NORMALISE PROFILE ID
    ================================================================ */

    function normaliseId(value) {
        if (!value) {
            return '';
        }

        return value
            .trim()
            .toUpperCase()
            .replace(/\s+/g, '');
    }


    /* ================================================================
       SEARCH API
    ================================================================ */

async function apiSearchById(profileId) {

    const url =
        `/api/search-by-profile-id/${encodeURIComponent(profileId)}`;

    console.log('Calling:', url);

    const response = await fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    });

    console.log('HTTP status:', response.status);
    console.log(
        'Content-Type:',
        response.headers.get('content-type')
    );

    if (!response.ok) {

        if (response.status === 404) {
            return {
                success: false,
                message: 'Profile not found.'
            };
        }

        const text = await response.text();

        console.error('Server response:', text);

        throw new Error(
            `Request failed with status ${response.status}`
        );
    }

    const data = await response.json();

    console.log('JSON response:', data);

    return data;
}


    /* ================================================================
       HTML ESCAPE
    ================================================================ */

    function escapeHtml(value) {
        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    /* ================================================================
       RENDER PROFILE
    ================================================================ */

    function renderProfile(user) {
        const resultIdLabel = document.getElementById('resultIdLabel');
        if (resultIdLabel) resultIdLabel.textContent = user.profile_id || '';
        const card = document.getElementById('sbidProfileCard');
        if (!card) return;
        card.innerHTML = window.dashboardProfileCardMarkup(user);
        window.mountProfileCardActions(card, user.id);
        const photo = card.querySelector('.profile-card-img');
        photo.addEventListener('error', () => {
            photo.src = window.profilePhotoUrl(null, user.gender);
        }, { once: true });
        if (window.lucide) window.lucide.createIcons();
    }

    /* ================================================================
       MAIN SEARCH
    ================================================================ */

    async function doSearch() {

    if (!input) {
        return;
    }

    const rawId = input.value;

    const profileId = normaliseId(rawId);

    /*
    |--------------------------------------------------------------------------
    | Validate
    |--------------------------------------------------------------------------
    */

    if (!profileId) {

        shakeInput();

        input.focus();

        return;
    }

    input.classList.remove('sbid-input-error');

    setSearching(true);

    showState(stateLoading);

    try {

        console.log('Searching profile:', profileId);

        const response =
            await apiSearchById(profileId);

        console.log('API response:', response);

        if (response.success && response.user) {

            renderProfile(response.user);

            showState(stateResult);

        } else {

            const notFoundId =
                document.getElementById('notFoundId');

            if (notFoundId) {
                notFoundId.textContent = profileId;
            }

            showState(stateNotFound);
        }

    } catch (error) {

        console.error(
            'Search by profile ID failed:',
            error
        );

        const notFoundId =
            document.getElementById('notFoundId');

        if (notFoundId) {
            notFoundId.textContent = profileId;
        }

        showState(stateNotFound);

    } finally {

        setSearching(false);
    }
}


    /* ================================================================
       EVENT LISTENERS
    ================================================================ */

    function init() {
        /* ------------------------------------------------------------
           Search button
        ------------------------------------------------------------ */

        if (searchBtn) {
            searchBtn.addEventListener(
                'click',
                doSearch
            );
        }


        /* ------------------------------------------------------------
           Enter key
        ------------------------------------------------------------ */

        if (input) {
            input.addEventListener(
                'keydown',
                function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        doSearch();
                    }

                    input.classList.remove(
                        'sbid-input-error'
                    );
                }
            );


            /* --------------------------------------------------------
               Input change
            -------------------------------------------------------- */

            input.addEventListener(
                'input',
                function () {
                    if (clearInputBtn) {
                        clearInputBtn.style.display =
                            this.value ? 'flex' : 'none';
                    }
                }
            );
        }


        /* ------------------------------------------------------------
           Clear input
        ------------------------------------------------------------ */

        if (clearInputBtn) {
            clearInputBtn.addEventListener(
                'click',
                function () {
                    if (input) {
                        input.value = '';
                        input.focus();
                    }

                    clearInputBtn.style.display = 'none';

                    showState(stateIdle);
                }
            );
        }


        /* ------------------------------------------------------------
           Try again
        ------------------------------------------------------------ */

        if (tryAgainBtn) {
            tryAgainBtn.addEventListener(
                'click',
                function () {
                    showState(stateIdle);

                    if (input) {
                        input.value = '';
                        input.focus();
                    }

                    if (clearInputBtn) {
                        clearInputBtn.style.display = 'none';
                    }
                }
            );
        }


        /* ============================================================
           LUCIDE ICONS
        ============================================================ */

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }


    /* ================================================================
       DOM READY
    ================================================================ */

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            init
        );
    } else {
        init();
    }

})();
