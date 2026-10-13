document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('advancedSearchForm');
    if (!form) return;
    for (const fields of [
        ['country_living_in', 'state_living_in', 'city_living_in'],
        ['partner_country', 'partner_state', 'partner_city'],
    ]) {
        const [country, state, city] = fields.map(field => document.getElementById(`search_${field}`));
        let version = 0;
        const reset = (select, label) => {
            select.replaceChildren(new Option(label, ''));
            select.disabled = true;
        };
        const load = async (select, url, selected, currentVersion) => {
            reset(select, 'Loading...');
            try {
                const response = await fetch(url, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('Location request failed');
                const rows = await response.json();
                if (currentVersion !== version) return;
                select.replaceChildren(new Option('All', ''));
                for (const row of rows) {
                    const option = new Option(row.name, row.name, false, row.name === selected);
                    option.dataset.id = row.id;
                    select.add(option);
                }
                select.disabled = false;
            } catch {
                if (currentVersion === version) reset(select, 'Unable to load. Select again.');
            }
        };
        const loadCities = async (selected = '', currentVersion = ++version) => {
            reset(city, 'Select State First');
            const id = state.selectedOptions[0]?.dataset.id;
            if (id) await load(city, form.dataset.citiesUrl.replace('__ID__', encodeURIComponent(id)), selected, currentVersion);
        };
        const loadStates = async (selectedState = '', selectedCity = '') => {
            const currentVersion = ++version;
            reset(state, 'Select Country First');
            reset(city, 'Select State First');
            const id = country.selectedOptions[0]?.dataset.id;
            if (!id) return;
            await load(state, form.dataset.statesUrl.replace('__ID__', encodeURIComponent(id)), selectedState, currentVersion);
            if (currentVersion === version) await loadCities(selectedCity, currentVersion);
        };
        country.addEventListener('change', () => loadStates());
        state.addEventListener('change', () => loadCities());
        loadStates(state.dataset.current || '', city.dataset.current || '');
    }
});
