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
    const interestBtn = document.getElementById('sbidInterestBtn');
    const shortlistBtn = document.getElementById('sbidShortlistBtn');

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

    console.log('Profile received:', user);

    /*
    |--------------------------------------------------------------------------
    | Basic values
    |--------------------------------------------------------------------------
    */

    const profileId = user.profile_id || '';

    const fullName = [
        user.first_name,
        user.last_name
    ].filter(Boolean).join(' ') || 'Member';


    /*
    |--------------------------------------------------------------------------
    | Result ID
    |--------------------------------------------------------------------------
    */

    const resultIdLabel = document.getElementById('resultIdLabel');

    if (resultIdLabel) {
        resultIdLabel.textContent = profileId;
    }


    /*
    |--------------------------------------------------------------------------
    | Cover image
    |--------------------------------------------------------------------------
    */

    const coverImg = document.getElementById('resultCoverImg');

    if (coverImg) {
        coverImg.src =
            user.cover ||
            'https://picsum.photos/seed/himrishteycover/680/160';

        coverImg.alt = fullName + ' cover photo';
    }


    /*
    |--------------------------------------------------------------------------
    | Profile photo
    |--------------------------------------------------------------------------
    */

    const avatar = document.getElementById('resultAvatar');

    if (avatar) {
        avatar.src =
            user.photo_url ||
            'https://himrishtey.com/img/boy.jpg';

        avatar.alt = fullName;
    }


    /*
    |--------------------------------------------------------------------------
    | Online status
    |--------------------------------------------------------------------------
    */

    const onlineBadge =
        document.getElementById('resultOnlineBadge');

    if (onlineBadge) {
        onlineBadge.style.display =
            user.online ? '' : 'none';
    }


    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    */

    const name =
        document.getElementById('resultName');

    if (name) {
        name.textContent = fullName;
    }


    /*
    |--------------------------------------------------------------------------
    | Verified
    |--------------------------------------------------------------------------
    */

    const verifiedBadge =
        document.getElementById('resultVerifiedBadge');

    if (verifiedBadge) {
        verifiedBadge.style.display =
            user.verified ? '' : 'none';
    }


    /*
    |--------------------------------------------------------------------------
    | Profile ID
    |--------------------------------------------------------------------------
    */

    const idTag =
        document.getElementById('resultIdTag');

    if (idTag) {
        idTag.textContent = profileId;
    }


    /*
    |--------------------------------------------------------------------------
    | Age
    |--------------------------------------------------------------------------
    */

    let ageText = 'Age not specified';

    if (user.age_years !== null && user.age_years !== undefined) {
        ageText = user.age_years + ' yrs';

        if (user.age_months) {
            ageText += ' ' + user.age_months + ' mo';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Quick stats
    |--------------------------------------------------------------------------
    */

    const statsRow =
        document.getElementById('resultStatsRow');

    if (statsRow) {

        const stats = [
            {
                icon: 'calendar',
                label: ageText
            },
            {
                icon: 'ruler',
                label: user.height || 'Height not specified'
            },
            {
                icon: 'map-pin',
                label: user.city || user.location || 'Location not specified'
            },
            {
                icon: 'briefcase',
                label: user.occupation || 'Occupation not specified'
            }
        ];

        statsRow.innerHTML = stats.map(function (item) {

            return `
                <span class="sbid-stat-pill">
                    <i data-lucide="${item.icon}"
                       width="12"
                       height="12"></i>
                    ${escapeHtml(item.label)}
                </span>
            `;

        }).join('');
    }


    /*
    |--------------------------------------------------------------------------
    | Details
    |--------------------------------------------------------------------------
    */

    const detailsGrid =
        document.getElementById('resultDetailsGrid');

    if (detailsGrid) {

        const details = [
            {
                label: 'Religion',
                value: user.religion
            },
            {
                label: 'Community',
                value: user.community
            },
            {
                label: 'Education',
                value: user.education
            },
            {
                label: 'Mother Tongue',
                value: user.mother_tongue || user.motherTongue
            },
            {
                label: 'Marital Status',
                value: user.marital_status || user.maritalStatus
            },
            {
                label: 'Annual Income',
                value: user.income
            }
        ];

        detailsGrid.innerHTML = details.map(function (item) {

            const value =
                item.value ||
                'Not specified';

            return `
                <div class="sbid-detail-item">

                    <span class="sbid-detail-label">
                        ${escapeHtml(item.label)}
                    </span>

                    <span class="sbid-detail-value ${
                        item.value ? '' : 'empty'
                    }">
                        ${escapeHtml(value)}
                    </span>

                </div>
            `;

        }).join('');
    }


    /*
    |--------------------------------------------------------------------------
    | View profile
    |--------------------------------------------------------------------------
    */

    const viewBtn =
        document.getElementById('sbidViewFullBtn');

    if (viewBtn) {

        viewBtn.href =
            `/view-profile/${encodeURIComponent(user.id)}`;
    }


    /*
    |--------------------------------------------------------------------------
    | Reinitialize Lucide
    |--------------------------------------------------------------------------
    */

    if (window.lucide) {
        lucide.createIcons();
    }
}

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
           INTEREST BUTTON
        ============================================================ */

        if (interestBtn) {
            interestBtn.addEventListener(
                'click',
                function () {
                    const isActive =
                        this.classList.toggle('active');

                    const textEl =
                        this.querySelector('span');

                    const iconEl =
                        this.querySelector('svg');

                    if (textEl) {
                        textEl.textContent =
                            isActive
                                ? 'Interested'
                                : 'Interest';
                    }

                    this.setAttribute(
                        'aria-pressed',
                        String(isActive)
                    );

                    if (iconEl) {
                        iconEl.style.fill =
                            isActive ? 'white' : 'none';
                    }
                }
            );
        }


        /* ============================================================
           SHORTLIST BUTTON
        ============================================================ */

        if (shortlistBtn) {
            shortlistBtn.addEventListener(
                'click',
                function () {
                    const isActive =
                        this.classList.toggle('active');

                    const textEl =
                        this.querySelector('span');

                    if (textEl) {
                        textEl.textContent =
                            isActive
                                ? 'Saved'
                                : 'Shortlist';
                    }

                    this.setAttribute(
                        'aria-pressed',
                        String(isActive)
                    );
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
