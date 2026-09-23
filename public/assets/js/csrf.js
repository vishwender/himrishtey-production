/* Keep forms and same-origin requests in sync with the current session token. */
(() => {
    if (window.siteCsrf) return;
    const nativeFetch = window.fetch.bind(window);
    let refreshing;
    const token = () => document.querySelector('meta[name="csrf-token"]')?.content || '';
    const update = value => {
        if (!value) return;
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) meta.content = value;
        document.querySelectorAll('input[name="_token"]').forEach(input => { input.value = value; });
    };
    const refresh = () => {
        if (!refreshing) {
            refreshing = nativeFetch('/csrf-token', {
                credentials: 'same-origin', cache: 'no-store', headers: { Accept: 'application/json' }
            }).then(async response => {
                if (!response.ok) throw new Error('Unable to refresh your session. Please reload the page.');
                const data = await response.json();
                if (typeof data.token !== 'string' || !data.token) throw new Error('Invalid session response. Please reload the page.');
                update(data.token);
                return data.token;
            }).finally(() => { refreshing = null; });
        }
        return refreshing;
    };
    async function withToken(request, value) {
        const headers = new Headers(request.headers);
        headers.set('X-CSRF-TOKEN', value);
        const type = headers.get('Content-Type') || '';
        let body;
        if (type.includes('multipart/form-data')) {
            body = await request.clone().formData();
            if (body.has('_token')) body.set('_token', value);
            headers.delete('Content-Type'); // Let the browser generate the multipart boundary.
        } else if (type.includes('application/x-www-form-urlencoded')) {
            body = new URLSearchParams(await request.clone().text());
            if (body.has('_token')) body.set('_token', value);
        } else if (type.includes('application/json')) {
            const text = await request.clone().text();
            try {
                const data = JSON.parse(text);
                if (data && typeof data === 'object' && !Array.isArray(data) && '_token' in data) data._token = value;
                body = JSON.stringify(data);
            } catch { body = text; }
        }
        return new Request(request, { headers, ...(body !== undefined ? { body } : {}) });
    }
    window.fetch = async (input, options) => {
        const request = new Request(input instanceof Request ? input : new URL(input, location.href), options);
        if (new URL(request.url).origin !== location.origin || /^(GET|HEAD|OPTIONS)$/i.test(request.method)) {
            return nativeFetch(request);
        }
        // Clone before sending so a rejected request can be retried once, including file uploads.
        const retry = request.clone();
        const response = await nativeFetch(await withToken(request, token()));
        if (response.status !== 419) return response;
        const freshToken = await refresh();
        return nativeFetch(await withToken(retry, freshToken));
    };
    const resubmitting = new WeakSet();
    document.addEventListener('submit', async event => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || event.defaultPrevented || form.method.toLowerCase() !== 'post' || new URL(form.action, location.href).origin !== location.origin) return;
        if (resubmitting.has(form)) { resubmitting.delete(form); return; }
        // AJAX form handlers have already prevented submission; this handles regular POST forms.
        event.preventDefault();
        try {
            await refresh();
            resubmitting.add(form);
            form.requestSubmit(event.submitter || undefined);
        } catch (error) {
            window.alert(error.message);
        }
    });
    window.addEventListener('pageshow', event => {
        if (event.persisted) refresh().catch(() => {});
    });
    window.siteCsrf = { refresh };
})();
