/**
 * @typedef {Object} FiltrySettings
 * @property {boolean} autosubmit
 * @property {boolean} ajax
 * @property {boolean} infinity_load
 * @property {string} rest_nonce
 * @property {string} rest_url
 * 
 * Get settings for the script and add jsDoc
 * typeins for ease of use
 * 
 * @return {FiltrySettings}
 */
export function getSettings() {
    return window.filtrySettings;
}