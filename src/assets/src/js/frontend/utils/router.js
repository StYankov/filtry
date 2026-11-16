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
        return getArchiveUrl();
    }

    /**
     * On Taxonomy pages if the term is still selected as a filer
     * then we want to keep the base url the same.
     */
    if(taxonomy in activefilters && activefilters[taxonomy].indexOf(term_slug) !== -1) {
        return getArchiveUrl();
    }

    // No longer a taxonomy page. Just shop page
    // Maybe this switch has to be moved somewhere else in the future.
    window.filtrySettings.is_taxonomy_page = false;
    window.filtrySettings.base_url = shop_page;

    /**
     * Evnet fired when the page is no longer a taxonomy page but a shop page
     */
    document.body.dispatchEvent(new CustomEvent('filtry.taxonomy.downgrade'));

    /**
     * If the taxonomy term is no longer in the selected filters
     * go back to the shop page
     */
    return shop_page;
}

/**
 * First page URL of the current archive, without query string and
 * without the /page/{n}/ segment of the discrete paginated archives.
 *
 * @return {string}
 */
export function getArchiveUrl() {
    const { base_url } = getSettings();

    if(base_url) {
        return base_url;
    }

    return window.location.href.split('?')[0].replace(/\/page\/\d+\/?$/, '/');
}

/**
 * URL of a discrete page (/page/{n}/) of the current archive with the active filters.
 *
 * @param {number} page
 *
 * @return {string}
 */
export function getPageUrl(page) {
    const filters = getActiveFilters();

    delete filters['paged'];

    const queryString = createQueryString(filters);
    const baseUrl = getBaseUrl().replace(/\/?$/, '/');
    const url = page > 1 ? `${baseUrl}page/${page}/` : baseUrl;

    return queryString.length > 0 ? `${url}?${queryString}` : url;
}
