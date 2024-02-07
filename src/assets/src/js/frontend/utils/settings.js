/**
 * @typedef {Object} FiltrySettings
 * @property {boolean} autosubmit
 * @property {boolean} ajax
 * @property {boolean} infinity_load
 * @property {boolean} mobile_filters
 * @property {string} rest_nonce
 * @property {string} rest_url
 * @property {boolean} is_taxonomy_page
 * @property {string} shop_page
 * @property {string} taxonomy
 * @property {string} term_slug
 * 
 * Get settings for the script and add jsDoc
 * typeins for ease of use
 * 
 * @return {FiltrySettings}
 */
export function getSettings() {
    return window.filtrySettings;
}