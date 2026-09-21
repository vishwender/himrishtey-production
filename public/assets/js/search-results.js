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
     MOCK DATA — replace with real API calls
  ---------------------------------------------------------------- */
  const PROFILES = window.searchResults || [];

  const PAGE_SIZE  = 8;
  const TOTAL_MOCK =  PROFILES.length;

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
    const SKIP    = ['user_id', 'gender', 'page_no', '_source'];
    const filters = [];

    params.forEach(function (value, key) {
      if (SKIP.includes(key) || !value) return;
      filters.push({
        name:  key.replace(/_/g, ' '),
        value: value.replace(/_/g, ' '),
        key:   key,
      });
    });

    /* Fallback mock filters if no URL params (demo mode) */
    if (!filters.length) {
      filters.push(
        { name: 'age',      value: '22–28',  key: 'age' },
        { name: 'religion', value: 'Hindu',  key: 'religion' },
        { name: 'location', value: 'Himachal Pradesh', key: 'location' }
      );
    }

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
     Mock API — returns a page of results
  ---------------------------------------------------------------- */
  function fetchPage(page) {
    return new Promise(function (resolve) {
      setTimeout(function () {
        const start   = page * PAGE_SIZE;
        const slice   = PROFILES.slice(start, start + PAGE_SIZE);
        const hasMore = start + PAGE_SIZE < TOTAL_MOCK;
        resolve({ success: true, user: slice, hasMore: hasMore });
      }, 600 + Math.random() * 400);
    });
  }

  /* ----------------------------------------------------------------
     Build a single profile-card — exact same HTML as index.html
  ---------------------------------------------------------------- */
  function buildProfileCard(profile, delay) {
    const article = document.createElement('article');
    article.className   = 'profile-card sr-animate';
    article.setAttribute('role', 'listitem');
    article.setAttribute('tabindex', '0');
    article.setAttribute('aria-label', profile.name + ', ' + profile.age);
    article.style.animationDelay = delay + 'ms';

    const imgUrl = profile.photo;

    let badges = '';
    if (profile.matchPct) {
      badges += '<span class="profile-card-match-badge">' + profile.matchPct + '% Match</span>';
    }
    if (profile.verified && !profile.matchPct) {
      badges += '<span class="profile-card-verified-badge"><i data-lucide="shield-check" width="12" height="12"></i></span>';
    }
    if (profile.online) {
      badges += '<span class="profile-card-online" aria-label="Online now"></span>';
    }

    const verifiedIcon = profile.verified
      ? ' <i data-lucide="shield-check" width="13" height="13" class="verified-icon"></i>'
      : '';

    article.innerHTML =
      '<div class="profile-card-img-wrap">' +
        '<img src="' + imgUrl + '" alt="' + profile.name + ', ' + profile.age + '" width="220" height="280" loading="lazy" class="profile-card-img" />' +
        badges +
        '<div class="profile-card-actions">' +
          '<button class="pca-btn like" aria-label="Like ' + profile.name + '">' +
            '<i data-lucide="heart" width="16" height="16"></i>' +
          '</button>' +
          '<button class="pca-btn interest" aria-label="Send interest to ' + profile.name + '">' +
            '<i data-lucide="user-plus" width="16" height="16"></i>' +
          '</button>' +
        '</div>' +
      '</div>' +
      '<div class="profile-card-body">' +
        '<h3 class="profile-card-name">' + profile.name + verifiedIcon + '</h3>' +
        '<p class="profile-card-meta"><i data-lucide="map-pin" width="12" height="12"></i> ' + profile.location + '</p>' +
        '<p class="profile-card-meta"><i data-lucide="briefcase" width="12" height="12"></i> ' + profile.occupation + ' • ' + profile.age + ' yrs</p>' +
        '<div class="profile-card-tags">' +
          '<span class="pct">' + profile.religion + '</span>' +
          '<span class="pct">' + profile.height + '</span>' +
          '<span class="pct">' + profile.education + '</span>' +
        '</div>' +
      '</div>';

    /* Like toggle */
    const likeBtn = article.querySelector('.pca-btn.like');
    likeBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      likeBtn.classList.toggle('active');
      const icon = likeBtn.querySelector('svg');
      if (icon) icon.style.fill = likeBtn.classList.contains('active') ? 'var(--color-primary)' : 'none';
    });

    /* Interest toggle */
    const intBtn = article.querySelector('.pca-btn.interest');
    intBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      intBtn.classList.toggle('active');
    });

    /* Card click → profile detail */
    article.addEventListener('click', function () {
      window.location.href = '/view-profile/' + profile.profile_id;
    });

    article.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
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

      if (currentPage === 0) {
        /* Hide skeleton, show grid */
        if (skeletonGrid) skeletonGrid.style.display = 'none';

        if (!response.success || !response.user.length) {
          if (emptyState) emptyState.style.display = '';
          if (countNum)   countNum.textContent     = '0';
          return;
        }

        if (resultGrid) resultGrid.style.display = '';
        if (countNum)   countNum.textContent     = TOTAL_MOCK;
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
      console.error('Failed to load profiles:', err);
      if (currentPage === 0) {
        if (skeletonGrid) skeletonGrid.style.display = 'none';
        if (emptyState)   emptyState.style.display   = '';
      }
    } finally {
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

  /* ----------------------------------------------------------------
     Sort change — re-render with mock sort
  ---------------------------------------------------------------- */
  function initSortChange() {
    if (!sortSelect) return;
    sortSelect.addEventListener('change', function () {
      /* In production: re-fetch with sort param */
      /* For demo: just clear and reload */
      currentPage = 0;
      allLoaded   = false;
      isLoading   = false;
      if (resultGrid)   resultGrid.innerHTML = '';
      if (endMsgEl)     endMsgEl.style.display = 'none';
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
