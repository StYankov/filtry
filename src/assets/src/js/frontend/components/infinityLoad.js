import { createQueryString, getActiveFilters } from '../utils/filters';
import { handleAjaxReload } from './ajaxSubmit';
import { pushRoute } from '../utils/router';
import { toggleLoader } from '../utils/loader';
import { getSettings } from '../utils/settings';

export function initInfinityLoad() {
    jQuery(document.body).on('click', '.filtry-load-more', onNextPage);
}

function onNextPage() {
    const activeFilters = getActiveFilters();

    if(activeFilters['paged']) {
        activeFilters['paged'] = parseInt(activeFilters['paged']) + 1;
    } else {
        activeFilters['paged'] = 2;
    }

    const queryString = createQueryString(activeFilters);

    handleInfinityLoad(queryString);
}

/**
 * @param {string} queryString
 */
export function handleInfinityLoad(queryString) {
    const settings = getSettings();
    const $productsList = jQuery('ul.products');

    if($productsList.length === 0) {
        console.error('Filtry: Could not find store products container');
        return;
    }

    toggleLoader(true);

    pushRoute(queryString);
    
    jQuery.ajax({
        url: `${settings.rest_url}?${queryString}`,
        method: 'GET',
        headers: {
            'X-WP-Nonce': settings.rest_nonce
        },
        success: function(res) {
            // Filters widget
            jQuery('.filtry').replaceWith(res.filters);

            // Products list
            $productsList.append(res.products);
            jQuery('.woocommerce-result-count').replaceWith(res.result_count);

            toggleLoader(false);

            if(res.current_page >= res.max_page) {
                jQuery('.filtry-lazy-load').remove();
            }
        },
        error: function() {
            toggleLoader(false);
        }
    });
}