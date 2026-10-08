document.addEventListener('DOMContentLoaded', function () {

    // Keep action menus outside the horizontally scrolling table so they are
    // not clipped by its scroll container or sticky action cells.
    document.querySelectorAll('.members-table .members-actions .dropdown').forEach(dropdown => {
        const menu = dropdown.querySelector('.dropdown-menu');
        const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
        if (!menu || !toggle) return;
        let placeholder = null;
        toggle.addEventListener('show.bs.dropdown', () => {
            placeholder = document.createComment('Member action menu');
            menu.replaceWith(placeholder);
            menu.classList.add('members-actions-flyout');
            document.body.appendChild(menu);
        });
        toggle.addEventListener('hidden.bs.dropdown', () => {
            placeholder?.replaceWith(menu);
            menu.classList.remove('members-actions-flyout');
            placeholder = null;
        });
        menu.addEventListener('keydown', event => {
            if (!['Escape', 'ArrowUp', 'ArrowDown'].includes(event.key)) return;
            event.preventDefault();
            event.stopPropagation();
            if (event.key === 'Escape') {
                window.bootstrap.Dropdown.getInstance(toggle)?.hide();
                toggle.focus();
                return;
            }
            const items = [...menu.querySelectorAll('.dropdown-item:not(:disabled):not(.disabled)')];
            const index = items.indexOf(document.activeElement);
            const next = event.key === 'ArrowDown' ? Math.min(index + 1, items.length - 1) : (index < 0 ? items.length - 1 : Math.max(index - 1, 0));
            items[next]?.focus();
        });
    });

    const deleteRequestModal = document.getElementById('deleteRequestModal');

    if (deleteRequestModal) {
        const form = document.getElementById('deleteRequestForm');
        const submit = form.querySelector('[type="submit"]');
        const reason = form.querySelector('[name="reason"]');
        const feedback = document.getElementById('deleteRequestFeedback');
        let submitting = false;
        let completed = false;
        let memberRow = null;

        const showFeedback = (message, success = false) => {
            feedback.textContent = message;
            feedback.className = `alert alert-${success ? 'success' : 'danger'}`;
        };

        deleteRequestModal.addEventListener('show.bs.modal', event => {
            form.reset();
            completed = false;
            feedback.className = 'alert d-none';
            feedback.textContent = '';
            const button = event.relatedTarget;
            memberRow = button?.closest('tr');
            const action = button?.dataset.action;
            form.removeAttribute('action');
            submit.disabled = !action;
            reason.disabled = false;
            if (action) form.setAttribute('action', action);
            document.getElementById('deleteRequestMemberName').textContent = button?.dataset.memberName || '';
            const profileId = button?.dataset.profileId;
            document.getElementById('deleteRequestProfileId').textContent = profileId ? `(${profileId})` : '';
            if (!action) showFeedback('Unable to select the member. Close this dialog and try again.');
        });

        deleteRequestModal.addEventListener('hide.bs.modal', event => {
            if (submitting) event.preventDefault();
        });

        deleteRequestModal.addEventListener('hidden.bs.modal', () => {
            if (completed) window.location.reload();
        });

        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (submitting || completed || !form.getAttribute('action') || !form.reportValidity()) return;
            submitting = true;
            submit.disabled = true;
            feedback.className = 'alert d-none';
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { Accept: 'application/json' },
                    body: new FormData(form),
                    credentials: 'same-origin',
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok || response.redirected) {
                    const errors = Object.values(data.errors || {}).flat();
                    const message = response.status === 419
                        ? 'Your session has expired. Refresh the page and try again.'
                        : (errors.join(' ') || data.message || 'Unable to submit the request. Refresh the page and try again.');
                    showFeedback(message);
                    return;
                }
                completed = true;
                memberRow?.remove();
                reason.disabled = true;
                showFeedback(data.message || 'Profile delete request raised successfully.', true);
            } catch {
                showFeedback('Unable to confirm submission. Check your connection and try again; duplicate requests are prevented.');
            } finally {
                submitting = false;
                submit.disabled = completed;
            }
        });
    }

    const themeToggle = document.getElementById('themeToggle');

    if (themeToggle) {
        const savedTheme = localStorage.getItem('admin-theme');

        applyTheme(savedTheme === 'dark' ? 'dark' : 'light');
        updateThemeIcon();

        themeToggle.addEventListener('click', function () {
            const currentTheme =
                document.documentElement.getAttribute('data-theme');

            if (currentTheme === 'dark') {
                applyTheme('light');
                localStorage.setItem('admin-theme', 'light');
            } else {
                applyTheme('dark');
                localStorage.setItem('admin-theme', 'dark');
            }

            updateThemeIcon();
        });
    }

    const adminWrapper = document.querySelector('.admin-wrapper');
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (adminWrapper && sidebarToggle) {
        const sidebar = document.getElementById('adminSidebar');
        const mobileSidebar = window.matchMedia('(max-width: 767.98px)');
        setSidebarState(true);
        mobileSidebar.addEventListener('change', () => setSidebarState(true));

        sidebarToggle.addEventListener('click', () => {
            setSidebarState(!adminWrapper.classList.contains('sidebar-collapsed'));
        });

        sidebar?.querySelectorAll('a, .nav-group-toggle, .nav-dropdown-toggle, [type="submit"]').forEach(item => {
            const label = item.textContent.trim().replace(/\s+/g, ' ');
            item.setAttribute('aria-label', label);
            item.setAttribute('title', label);
        });

        const flyout = document.createElement('div');
        flyout.className = 'sidebar-flyout';
        flyout.hidden = true;
        document.body.appendChild(flyout);
        let activeFlyout = null;

        const closeFlyout = (restoreFocus = false) => {
            if (!activeFlyout) return;
            const { button, menu, placeholder, expanded } = activeFlyout;
            placeholder.replaceWith(menu);
            button.setAttribute('aria-expanded', expanded);
            button.removeAttribute('aria-controls');
            flyout.replaceChildren();
            flyout.hidden = true;
            activeFlyout = null;
            if (restoreFocus) button.focus();
        };

        sidebar?.querySelectorAll('.nav-group-toggle, .nav-dropdown-toggle').forEach((button, index) => {
            button.addEventListener('click', event => {
                if (mobileSidebar.matches || !adminWrapper.classList.contains('sidebar-collapsed')) return;
                event.preventDefault();
                event.stopImmediatePropagation();
                const wasOpen = activeFlyout?.button === button;
                closeFlyout();
                if (wasOpen) return;
                const menu = button.parentElement.querySelector(':scope > .nav-submenu, :scope > .nav-dropdown-menu');
                if (!menu) return;
                const placeholder = document.createComment('Sidebar submenu');
                const expanded = button.getAttribute('aria-expanded') || 'false';
                menu.replaceWith(placeholder);
                const heading = document.createElement('div');
                heading.className = 'sidebar-flyout-heading';
                heading.textContent = button.getAttribute('aria-label');
                flyout.id = `sidebar-flyout-${index}`;
                flyout.replaceChildren(heading, menu);
                flyout.hidden = false;
                button.setAttribute('aria-expanded', 'true');
                button.setAttribute('aria-controls', flyout.id);
                activeFlyout = { button, menu, placeholder, expanded };
                const bounds = button.getBoundingClientRect();
                flyout.style.left = `${sidebar.getBoundingClientRect().right + 8}px`;
                flyout.style.top = `${Math.max(8, Math.min(bounds.top, window.innerHeight - flyout.offsetHeight - 8))}px`;
                menu.querySelector('a, button')?.focus();
            }, true);
        });

        document.addEventListener('click', event => {
            if (activeFlyout && !flyout.contains(event.target) && !activeFlyout.button.contains(event.target)) closeFlyout();
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && activeFlyout) {
                event.preventDefault();
                closeFlyout(true);
            }
        });
        document.addEventListener('focusin', event => {
            if (activeFlyout && !flyout.contains(event.target) && event.target !== activeFlyout.button) closeFlyout();
        });
        sidebar?.addEventListener('scroll', () => closeFlyout());
        window.addEventListener('resize', () => closeFlyout());
        sidebarToggle.addEventListener('click', () => closeFlyout());
    }

    function setSidebarState(isCollapsed) {
        adminWrapper.classList.toggle('sidebar-collapsed', isCollapsed);
        updateSidebarToggle(isCollapsed);
    }

    function updateSidebarToggle(isCollapsed) {
        sidebarToggle.setAttribute('aria-expanded', String(!isCollapsed));
        sidebarToggle.setAttribute(
            'aria-label',
            isCollapsed ? 'Open full menu' : 'Collapse menu'
        );

        const icon = sidebarToggle.querySelector('i');

        if (icon) {
            icon.className = isCollapsed
                ? 'bi bi-layout-sidebar'
                : 'bi bi-layout-sidebar-inset';
        }
    }

    function applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            return;
        }

        document.documentElement.removeAttribute('data-theme');
        document.documentElement.setAttribute('data-bs-theme', 'light');
    }

    function updateThemeIcon() {

        const isDark =
            document.documentElement.getAttribute('data-theme') === 'dark';

        const icon = themeToggle.querySelector('i');

        if (!icon) {
            return;
        }

        icon.className = isDark
            ? 'bi bi-sun'
            : 'bi bi-moon';
    }
});
