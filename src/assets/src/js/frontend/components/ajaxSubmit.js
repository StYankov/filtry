import { getSettings } from '../utils/settings';
import { toggleLoader } from '../utils/loader';
import { pushRoute } from '../utils/router';
import { getActiveFilters, createQueryString } from '../utils/filters';

/**
 * @param {string} queryString
 */
export function handleAjaxReload(queryString) {
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
            jQuery('.filtry').replaceWith(res.filters);
            $productsList.html(res.products);
            jQuery('.woocommerce-result-count').replaceWith(res.result_count);
            jQuery('.woocommerce-pagination').replaceWith(res.pagination);

            toggleLoader(false);
        },
        error: function() {
            toggleLoader(false);
        }
    });
}

export function initAjaxReload() {
    jQuery(document.body).on('click', '.woocommerce-pagination a', handlePagination);
}

/**
 * 
 * @param {Event} e 
 */
function handlePagination(e) {
    e.preventDefault();

    const regex = /\/page\/(\d+)/;
    const url = e.target.href;
    const match = url.match(regex);
    
    let newPage = null; 

    if(match) {
        newPage = parseInt(match[1]);
    } else if(url.split('?').length > 1) {
        const urlQuery = url.split('?')[1];
        const query = new URLSearchParams(urlQuery);

        if(query.has('paged')) {
            newPage = parseInt(query.get('paged'));
        } else if(query.has('page')) {
            newPage = parseInt(query.get('page'));
        }
    }

    if(Number.isNaN(newPage)) {
        console.error('Filtry: Could not determine page number');
        return;
    }
    
    const activeFilters = getActiveFilters();

    activeFilters['paged'] = newPage;

    handleAjaxReload(
        createQueryString(activeFilters)
    );
}