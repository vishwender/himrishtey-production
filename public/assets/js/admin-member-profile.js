document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('.crm-profile');
    if (!root) return;
    const nav = root.querySelector('.crm-tabs');
    const buttons = [...nav.querySelectorAll('[data-profile-tab]')];
    const sections = [...root.querySelectorAll('[data-crm-section]')];
    const select = (key) => {
        buttons.forEach(button => button.setAttribute('aria-pressed', String(button.dataset.profileTab === key)));
        sections.forEach(section => { section.hidden = section.dataset.crmSection !== key; });
    };
    const revealHash = () => {
        const id = decodeURIComponent(location.hash.slice(1));
        if (!id) return false;
        const target = document.getElementById(id);
        const section = target?.closest('[data-crm-section]');
        if (!section) return false;
        select(section.dataset.crmSection);
        target.scrollIntoView({ block: 'nearest' });
        return true;
    };
    buttons.forEach(button => button.addEventListener('click', () => select(button.dataset.profileTab)));
    nav.hidden = false;
    if (!revealHash()) select('overview');
    window.addEventListener('hashchange', revealHash);
});
