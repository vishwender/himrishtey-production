/* Global, accessible toast feedback used instead of browser alert dialogs. */
(function (window, document) {
    'use strict';

    var timer;
    var toast;

    function getToast() {
        if (toast) return toast;

        toast = document.createElement('div');
        toast.className = 'hr-toast';
        toast.setAttribute('role', 'status');
        toast.setAttribute('aria-live', 'polite');
        toast.innerHTML = '<span class="hr-toast__icon" aria-hidden="true">✓</span><span class="hr-toast__message"></span>';
        document.body.appendChild(toast);
        return toast;
    }

    function show(message, type, duration) {
        var element = getToast();
        var kind = type || 'success';
        var icon = kind === 'error' ? '!' : kind === 'info' ? 'i' : '✓';

        window.clearTimeout(timer);
        element.className = 'hr-toast hr-toast--' + kind;
        element.querySelector('.hr-toast__icon').textContent = icon;
        element.querySelector('.hr-toast__message').textContent = String(message || 'Something went wrong.');

        window.requestAnimationFrame(function () { element.classList.add('is-visible'); });
        timer = window.setTimeout(function () { element.classList.remove('is-visible'); }, duration || 4000);
    }

    window.HimRishteyToast = {
        show: show,
        success: function (message, duration) { show(message, 'success', duration); },
        error: function (message, duration) { show(message, 'error', duration); },
        info: function (message, duration) { show(message, 'info', duration); }
    };
})(window, document);
