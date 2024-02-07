import { getSettings } from '../utils/settings';
import { toggleLoader } from '../utils/loader';
import { pushRoute } from '../utils/router';
import { createQueryString, getActiveFilters } from './filters';

/**
 * @typedef {Object} ShopResponse
 * @property {string} filters Filters HTML
 * @property {string} products Products HTML
 * @property {string} result_count
 * @property {string} pagination
 * @property {number} current_page
 * @property {number} max_page
 * 
 * @param {string} queryString
 * @param {function(ShopResponse)|null} onSuccess
 * @param {function|null} onFail
 */
export function getShop(queryString, onSuccess = null, onFail = null) {
    const $productsList = jQuery('ul.products');

    if($productsList.length === 0) {
        console.error('Filtry: Could not find store products container');
        return;
    }

    const settings = getSettings();

    toggleLoader(true);

    pushRoute(queryString);

    // This full query string regardles of taxonomy page
    const requestQueryString = createQueryString(getActiveFilters(), false);

    jQuery.ajax({
        url: `${settings.rest_url}?${requestQueryString}`,
        method: 'GET',
        headers: {
            'X-WP-Nonce': settings.rest_nonce
        },
        success: function(res) {
            toggleLoader(false);

            jQuery('.filtry__filters').replaceWith(res.filters);
            jQuery('.woocommerce-result-count').replaceWith(res.result_count);

            if(typeof onSuccess === 'function') {
                onSuccess(res);
            } else {
                jQuery('.filtry').replaceWith(res.filters);
                $productsList.html(res.products);
                jQuery('.woocommerce-pagination').replaceWith(res.pagination);
            }
        },
        error: function(err) {
            toggleLoader(false);

            if(typeof onFail === 'function') {
                onFail(err);
            }
        }
    });
}