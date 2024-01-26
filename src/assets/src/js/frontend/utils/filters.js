/**
 * @typedef {Object.<string, Array<string>>} ActiveFilters
 * 
 * @return {ActiveFilters}
 */
export function getActiveFilters() {
    const $filters = jQuery('.filtry__filter');

    const currentQuery = new URLSearchParams(window.location.search);

    const activeFilters = {};

    $filters.each((i, el) => {
        if(el.classList.contains('filtry__filter--checkbox') || el.classList.contains('filtry__filter--radio')) {
            const $checkedInputs = jQuery(el).find('input:checked');

            $checkedInputs.each((j, input) => {
                const taxonomy = input.dataset.taxonomy;
                const value = input.value;

                if(taxonomy in activeFilters) {
                    activeFilters[taxonomy].push(value);
                } else {
                    activeFilters[taxonomy] = [value];
                }
            });
        } else {

        }
    });

    if(currentQuery.has('paged')) {
        activeFilters.paged = parseInt(currentQuery.get('paged'));
    }

    if(currentQuery.has('orderby')) {
        activeFilters.orderby = currentQuery.get('orderby');
    }

    return activeFilters;
}

/**
 * @param {ActiveFilters} filters 
 * 
 * @return {string}
 */
export function createQueryString(filters) {
    const query = new URLSearchParams();

    for(const key of Object.keys(filters)) {
        const value = Array.isArray(filters[key]) ? filters[key].join(',') : filters[key];

        query.set(key, value);
    }

    return query.toString();
}