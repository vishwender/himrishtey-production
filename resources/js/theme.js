const THEME_STORAGE_KEY = 'site-theme';
const LEGACY_THEME_KEYS = ['public-theme', 'hr-theme'];
const initializedButtons = new WeakSet();

export function getStoredTheme() {
    try {
        const saved = localStorage.getItem(THEME_STORAGE_KEY) || LEGACY_THEME_KEYS
            .map((key) => localStorage.getItem(key))
            .find(Boolean);

        if (saved === 'dark' || saved === 'light') return saved;
        return window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    } catch (error) {
        return 'light';
    }
}

export function applyTheme(theme) {
    const nextTheme = theme === 'dark' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', nextTheme);

    try {
        localStorage.setItem(THEME_STORAGE_KEY, nextTheme);
        LEGACY_THEME_KEYS.forEach((key) => {
            if (localStorage.getItem(key) !== nextTheme) {
                localStorage.setItem(key, nextTheme);
            }
        });
    } catch (error) {
        // Ignore storage failures in private browsing or locked down contexts.
    }

    document.querySelectorAll('[data-theme-toggle], [data-public-theme]').forEach((button) => {
        const isDark = nextTheme === 'dark';
        button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        button.setAttribute('aria-pressed', String(isDark));
    });

    return nextTheme;
}

export function initTheme() {
    const preferredTheme = getStoredTheme();
    applyTheme(preferredTheme);

    document.querySelectorAll('[data-theme-toggle], [data-public-theme]').forEach((button) => {
        if (initializedButtons.has(button)) return;
        initializedButtons.add(button);
        button.addEventListener('click', () => {
            const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            applyTheme(nextTheme);
        });
    });
}

if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        initTheme();
    });
}

export default {
    getStoredTheme,
    applyTheme,
    initTheme,
};
