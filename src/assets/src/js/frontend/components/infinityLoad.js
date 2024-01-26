import { createQueryString, getActiveFilters } from '../utils/filters';
import { pushRoute } from '../utils/router';
import { toggleLoader } from '../utils/loader';
import { getSettings } from '../utils/settings';
import { getShop } from '../utils/ajax';

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
    getShop(queryString, (res) => {
        if(res.current_page >= res.max_page) {
            const $productsList = jQuery('ul.products');

            jQuery('.filtry-lazy-load').remove();
            $productsList.append(res.products);
        } 
    });
}