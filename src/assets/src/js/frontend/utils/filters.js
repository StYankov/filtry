import { getSettings } from "./settings";

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
 * @param {boolean} ignoreTaxonomyTerm Removes the taxonomy term from the query string. Only applicable on taxonomy pages
 * 
 * @return {string}
 */
export function createQueryString(filters, ignoreTaxonomyTerm = true) {
    const query = new URLSearchParams();
    const { is_taxonomy_page, taxonomy, term_slug } = getSettings();

    for(const key of Object.keys(filters)) {
        let value = Array.isArray(filters[key]) ? filters[key] : [filters[key]];

        if(ignoreTaxonomyTerm && is_taxonomy_page && taxonomy === key) {
            value = value.filter(slug => slug !== term_slug);
        }

        if(value.length === 0) {
            continue;
        }

        query.set(key, value.join(','));
    }

    return query.toString();
}