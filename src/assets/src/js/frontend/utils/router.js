import { getActiveFilters, createQueryString } from './filters';
import { getSettings } from './settings';
/**
 * 
 * @param {string} queryString 
 * @param {object} data
 */
export function pushRoute(queryString, data = {}) {
    const baseUrl = getBaseUrl();

    if(queryString.length === 0) {
        window.history.pushState(data, '', baseUrl);
    } else {
        window.history.pushState(data, '', `${baseUrl}?${queryString}`);
    }
}

export function getBaseUrl() {
    const activefilters = getActiveFilters();
    const { shop_page, is_taxonomy_page, taxonomy, term_slug} = getSettings();

    if(!is_taxonomy_page) {
        return window.location.href.split('?')[0];
    }

    /**
     * On Taxonomy pages if the term is still selected as a filer
     * then we want to keep the base url the same.
     */
    if(taxonomy in activefilters && activefilters[taxonomy].indexOf(term_slug) !== -1) {
        return window.location.href.split('?')[0];
    }

    // No longer a taxonomy page. Just shop page
    window.filtrySettings.is_taxonomy_page = false;

    /**
     * If the taxonomy term is no longer in the selected filters
     * go back to the shop page
     */
    return shop_page;
}