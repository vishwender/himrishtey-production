(() => {
    const modal = document.getElementById('profileSearchModal');
    const openButton = document.getElementById('openProfileSearch');
    const closeButton = document.getElementById('closeProfileSearch');
    const input = document.getElementById('profileSearchInput');
    const results = document.getElementById('profileSearchResults');
    let timer;
    let requestId = 0;

    if (!modal || !openButton || !closeButton || !input || !results) return;

    const setEmptyState = (message) => {
        results.replaceChildren();
        const state = document.createElement('div');
        state.className = 'search-empty';
        state.textContent = message;
        results.appendChild(state);
    };

    const closeModal = () => {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('search-modal-open');
        openButton.focus();
    };

    openButton.addEventListener('click', (event) => {
        event.preventDefault();
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('search-modal-open');
        window.setTimeout(() => input.focus(), 0);
    });

    closeButton.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => { if (event.target === modal) closeModal(); });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('show')) closeModal();
    });

    input.addEventListener('input', () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(() => searchProfiles(input.value), 300);
    });

    async function searchProfiles(value) {
        const keyword = value.trim();
        if (keyword.length < 2) {
            setEmptyState('Start typing to search profiles.');
            return;
        }

        const activeRequest = ++requestId;
        setEmptyState('Searching profiles…');

        try {
            const response = await fetch(`search-home-profile?keyword=${encodeURIComponent(keyword)}`, {
                headers: { Accept: 'application/json' }
            });
            if (!response.ok) throw new Error('Search request failed.');

            const profiles = await response.json();
            if (activeRequest !== requestId) return;

            results.replaceChildren();
            if (!profiles.length) {
                setEmptyState('No matching profiles found.');
                return;
            }

            profiles.forEach((profile) => {
                const item = document.createElement('a');
                item.className = 'search-item';
                item.href = `view-profile/${encodeURIComponent(profile.profile_id)}`;

                const image = document.createElement('img');
                image.src = profile.photo || '/images/default-avatar.png';
                image.alt = profile.full_name ? `${profile.full_name}'s profile photo` : 'Profile photo';

                const details = document.createElement('div');
                details.className = 'search-details';
                const name = document.createElement('h4');
                name.textContent = profile.full_name || 'Member profile';
                const id = document.createElement('p');
                id.textContent = profile.profile_id || '';
                details.append(name, id);

                const arrow = document.createElement('i');
                arrow.className = 'search-item-arrow';
                arrow.setAttribute('data-lucide', 'chevron-right');
                arrow.setAttribute('aria-hidden', 'true');
                item.append(image, details, arrow);
                results.appendChild(item);
            });

            if (window.lucide) window.lucide.createIcons();
        } catch (error) {
            if (activeRequest !== requestId) return;
            setEmptyState('Unable to search profiles right now. Please try again.');
        }
    }
})();
