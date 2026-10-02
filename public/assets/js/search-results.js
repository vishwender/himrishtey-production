/* ================================================================
   SEARCH RESULTS PAGE — search-results.js
   Mirrors SearchResultPage.dart logic:
   - Reads search params (from URL or sessionStorage)
   - Renders active filter chips
   - Loads profiles in pages (infinite scroll)
   - Uses same profile-card pattern as index.html
   ================================================================ */

(function () {
  'use strict';

  /* ----------------------------------------------------------------
     Results supplied by the website search controller
  ---------------------------------------------------------------- */
  const PROFILES = window.searchResults || [];

  const PAGE_SIZE  = 8;
  const TOTAL_RESULTS =  PROFILES.length;

  let sortedProfiles = PROFILES.slice();
  let resultVersion = 0;
  let currentPage  = 0;
  let isLoading    = false;
  let allLoaded    = false;
  let activeFilters = [];

  /* ----------------------------------------------------------------
     DOM refs
  ---------------------------------------------------------------- */
  const chipContainer  = document.getElementById('srFilterChips');
  const countNum       = document.getElementById('srCountNum');
  const skeletonGrid   = document.getElementById('srSkeletonGrid');
  const resultGrid     = document.getElementById('srResultGrid');
  const emptyState     = document.getElementById('srEmpty');
  const loadMoreEl     = document.getElementById('srLoadMore');
  const endMsgEl       = document.getElementById('srEndMsg');
  const sortSelect     = document.getElementById('srSort');

  /* ----------------------------------------------------------------
     Read search params — from URL query string
     e.g. ?age_from=22&age_to=28&religion=Hindu&location=Shimla
  ---------------------------------------------------------------- */
  function readSearchParams() {
    const params  = new URLSearchParams(window.location.search);
    const SKIP    = ['user_id', 'gender', 'page_no', '_source', 'sort'];
    const filters = [];

    params.forEach(function (value, key) {
      if (SKIP.includes(key) || !value) return;
      const incomeNames = { annual_income: 'Annual income from', annual_income_to: 'Annual income to' };
      let displayValue = value.replace(/_/g, ' ');
      if (incomeNames[key]) {
        const legacyBand = value.match(/^\d+-(\d+) lakhs$/);
        displayValue = legacyBand ? legacyBand[1] + ' LPA' : (/^\d+$/.test(value) ? value + ' LPA' : value);
      }
      filters.push({
        name: incomeNames[key] || key.replace(/_/g, ' '),
        value: displayValue,
        key: key,
      });
    });

    return filters;
  }

  /* ----------------------------------------------------------------
     Capitalise first letter
  ---------------------------------------------------------------- */
  function cap(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  /* ----------------------------------------------------------------
     Render filter chips
  ---------------------------------------------------------------- */
  function renderFilterChips(filters) {
    if (!chipContainer) return;
    chipContainer.innerHTML = '';

    if (!filters.length) return;

    filters.forEach(function (f) {
      const chip = document.createElement('span');
      chip.className = 'sr-filter-chip';
      chip.setAttribute('role', 'listitem');
      chip.innerHTML =
        '<span class="chip-label">' + cap(f.name) + '</span>' +
        '&nbsp;' + cap(f.value);
      chipContainer.appendChild(chip);
    });

    /* Clear all chip */
    const clearChip = document.createElement('button');
    clearChip.className = 'sr-filter-chip clear-all';
    clearChip.setAttribute('aria-label', 'Clear all filters');
    clearChip.innerHTML = '<svg data-lucide="x" width="12" height="12"></svg> Clear all';
    clearChip.addEventListener('click', function () {
      window.location.href = window.searchReturnUrl || window.quickSearchUrl;
    });
    chipContainer.appendChild(clearChip);

    if (window.lucide) lucide.createIcons({ nodes: [chipContainer] });
  }

  /* ----------------------------------------------------------------
     Return a page from the selected result order
  ---------------------------------------------------------------- */
  function fetchPage(page) {
    const profiles = sortedProfiles;
    return new Promise(function (resolve) {
      setTimeout(function () {
        const start   = page * PAGE_SIZE;
        const slice   = profiles.slice(start, start + PAGE_SIZE);
        const hasMore = start + PAGE_SIZE < TOTAL_RESULTS;
        resolve({ success: true, user: slice, hasMore: hasMore });
      }, 600 + Math.random() * 400);
    });
  }

  /* ----------------------------------------------------------------
     Build a single profile-card — exact same HTML as index.html
  ---------------------------------------------------------------- */
  function buildProfileCard(profile, delay) {
    const article = document.createElement('article');
    article.className   = 'profile-card dashboard-profile-card sr-animate';
    article.setAttribute('role', 'listitem');
    article.setAttribute('tabindex', '0');
    article.setAttribute('aria-label', profile.name + ', ' + profile.age);
    article.style.animationDelay = delay + 'ms';

    article.innerHTML = window.dashboardProfileCardMarkup(profile);

    const photoImage = article.querySelector('.profile-card-img');
    photoImage.addEventListener('error', function () {
      photoImage.src = window.profilePhotoUrl(null, profile.gender);
    }, { once: true });

    window.mountProfileCardActions(article, profile.id);

    /* Card click → profile detail */
    article.addEventListener('click', function (event) {
      if (event.target.closest('a, button')) return;
      window.location.href = '/view-profile/' + profile.profile_id;
    });

    article.addEventListener('keydown', function (e) {
      if (e.target === article && (e.key === 'Enter' || e.key === ' ')) {
        e.preventDefault();
        window.location.href = '/view-profile/' + profile.profile_id;
      }
    });

    return article;
  }

  /* ----------------------------------------------------------------
     Load a page and append cards
  ---------------------------------------------------------------- */
  async function loadPage() {
    if (isLoading || allLoaded) return;
    isLoading = true;
    const version = resultVersion;

    if (currentPage === 0) {
      /* First load — show skeleton */
      if (skeletonGrid) skeletonGrid.style.display = '';
      if (resultGrid)   resultGrid.style.display   = 'none';
    } else {
      /* Subsequent pages — show bottom loader */
      if (loadMoreEl) loadMoreEl.style.display = '';
    }

    try {
      const response = await fetchPage(currentPage);
      if (version !== resultVersion) return;

      if (currentPage === 0) {
        /* Hide skeleton, show grid */
        if (skeletonGrid) skeletonGrid.style.display = 'none';

        if (!response.success || !response.user.length) {
          if (emptyState) emptyState.style.display = '';
          if (countNum)   countNum.textContent     = '0';
          return;
        }

        if (resultGrid) resultGrid.style.display = '';
        if (countNum)   countNum.textContent     = TOTAL_RESULTS;
      }

      if (response.success && response.user.length) {
        response.user.forEach(function (profile, i) {
          const card = buildProfileCard(profile, i * 40);
          if (resultGrid) resultGrid.appendChild(card);
        });

        if (window.lucide) lucide.createIcons({ nodes: [resultGrid] });

        currentPage++;
        allLoaded = !response.hasMore;
      }

    } catch (err) {
      if (version !== resultVersion) return;
      console.error('Failed to load profiles:', err);
      if (currentPage === 0) {
        if (skeletonGrid) skeletonGrid.style.display = 'none';
        if (emptyState)   emptyState.style.display   = '';
      }
    } finally {
      if (version !== resultVersion) return;
      isLoading = false;
      if (loadMoreEl) loadMoreEl.style.display = 'none';

      if (allLoaded && endMsgEl) {
        endMsgEl.style.display = '';
        if (window.lucide) lucide.createIcons({ nodes: [endMsgEl] });
      }
    }
  }

  /* ----------------------------------------------------------------
     Infinite scroll — observe sentinel at bottom
  ---------------------------------------------------------------- */
  function initInfiniteScroll() {
    if (!window.IntersectionObserver) {
      /* Fallback: load on scroll */
      window.addEventListener('scroll', function () {
        const scrolledToBottom =
          window.innerHeight + window.scrollY >= document.body.offsetHeight - 300;
        if (scrolledToBottom) loadPage();
      });
      return;
    }

    const sentinel = document.createElement('div');
    sentinel.style.height = '1px';
    document.body.appendChild(sentinel);

    const observer = new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting) loadPage();
    }, { rootMargin: '400px' });

    observer.observe(sentinel);
  }

  /* Sort the complete result set before slicing pages. */
  function applySort(value) {
    const sort = ['newest', 'age_asc', 'age_desc'].includes(value) ? value : 'relevance';
    sortedProfiles = PROFILES.slice();
    if (sort === 'newest') {
      // Member IDs increase as profiles are registered.
      sortedProfiles.sort((a, b) => Number(b.id) - Number(a.id));
    } else if (sort === 'age_asc' || sort === 'age_desc') {
      const age = profile => profile.age === null || profile.age === undefined || profile.age === ''
        ? null : Number(profile.age);
      sortedProfiles.sort((a, b) => {
        const first = age(a), second = age(b);
        const firstMissing = first === null || !Number.isFinite(first);
        const secondMissing = second === null || !Number.isFinite(second);
        if (firstMissing || secondMissing) return Number(firstMissing) - Number(secondMissing);
        return sort === 'age_asc' ? first - second : second - first;
      });
    }
    if (sortSelect) sortSelect.value = sort;
    return sort;
  }

  function initSortChange() {
    applySort(new URLSearchParams(window.location.search).get('sort'));
    if (!sortSelect) return;
    sortSelect.addEventListener('change', function () {
      const sort = applySort(sortSelect.value);
      const url = new URL(window.location.href);
      if (sort === 'relevance') url.searchParams.delete('sort');
      else url.searchParams.set('sort', sort);
      window.history.replaceState(null, '', url);
      resultVersion++;
      currentPage = 0;
      allLoaded = false;
      isLoading = false;
      if (resultGrid) resultGrid.innerHTML = '';
      if (endMsgEl) endMsgEl.style.display = 'none';
      if (emptyState) emptyState.style.display = 'none';
      loadPage();
    });
  }

  /* ----------------------------------------------------------------
     INIT
  ---------------------------------------------------------------- */
  function init() {
    activeFilters = readSearchParams();
    renderFilterChips(activeFilters);
    initSortChange();
    initInfiniteScroll();
    loadPage();  /* Load first page immediately */

    if (window.lucide) lucide.createIcons();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
