(() => {
  const states = new Map();
  const config = window.profileCardActionsConfig;
  async function request(url, body) {
    const response = await fetch(url, {
      method: body ? 'POST' : 'GET',
      headers: {
        Accept: 'application/json',
        ...(body ? { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } : {})
      },
      ...(body ? { body: JSON.stringify(body) } : {})
    });
    const data = await response.json();
    if (!response.ok) throw new Error(data.message || 'Unable to update profile. Please try again.');
    return data;
  }
  function paint(state) {
    state.buttons.forEach(({ button, action }) => {
      const active = action === 'like' ? state.liked : state.shortlisted;
      button.disabled = !state.ready || state.busy || (action === 'shortlist' && active);
      button.classList.toggle('is-selected', !!active);
      button.setAttribute('aria-pressed', String(!!active));
      const label = action === 'like' ? (active ? 'Unlike profile' : 'Like profile') : (active ? 'Shortlisted' : 'Shortlist profile');
      button.title = label;
      button.setAttribute('aria-label', label);
    });
  }
  window.mountProfileCardActions = (card, memberId) => {
    const id = String(memberId || '');
    if (!/^\d+$/.test(id) || card.querySelector('.profile-card-actions-live')) return;
    const wrap = card.querySelector('.profile-card-img-wrap');
    if (!wrap) return;
    let state = states.get(id);
    if (!state) {
      state = { buttons: [], ready: false, busy: false };
      states.set(id, state);
      Promise.all([
        request(config.checkLike.replace('__ID__', encodeURIComponent(id))),
        request(config.checkShortlist + '?id=' + encodeURIComponent(id))
      ]).then(([like, shortlist]) => {
        state.liked = like.liked === true;
        state.shortlisted = shortlist.shortlisted === true;
        state.ready = true;
        paint(state);
      }).catch(() => {
        state.buttons.forEach(({ button }) => { button.title = 'Unable to load saved state. Refresh to retry.'; });
      });
    }
    const controls = document.createElement('div');
    controls.className = 'profile-card-actions profile-card-actions-live';
    for (const action of ['like', 'shortlist']) {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'pca-btn card-' + action;
      button.innerHTML = action === 'like'
        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/></svg>'
        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 21l-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>';
      button.addEventListener('keydown', event => event.stopPropagation());
      button.addEventListener('click', async event => {
        event.preventDefault();
        event.stopPropagation();
        if (!state.ready || state.busy) return;
        state.busy = true;
        paint(state);
        try {
          const data = await request(action === 'like' ? config.like : config.shortlist, { id });
          if (action === 'like') {
            if (!['liked', 'unliked'].includes(data.status)) throw new Error(data.message || 'Unable to update like.');
            state.liked = data.status === 'liked';
          } else {
            if (!['success', 'already_shortlisted'].includes(data.status)) throw new Error(data.message || 'Unable to shortlist.');
            state.shortlisted = true;
          }
          window.HimRishteyToast.success(data.message || 'Profile updated.');
        } catch (error) {
          window.HimRishteyToast.error(error.message);
        } finally {
          state.busy = false;
          paint(state);
        }
      });
      state.buttons.push({ button, action });
      controls.appendChild(button);
    }
    wrap.appendChild(controls);
    paint(state);
  };
  document.querySelectorAll('[data-profile-card-id]').forEach(card => {
    window.mountProfileCardActions(card, card.dataset.profileCardId);
  });
})();
