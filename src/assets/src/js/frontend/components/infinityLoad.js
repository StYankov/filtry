import { createQueryString, getActiveFilters } from '../utils/filters';
import { getShop } from '../utils/ajax';

export function initInfinityLoad() {
    $(document.body).on('click', '.filtry-load-more', onNextPage);
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
        const $productsList = $('ul.products');
        $productsList.append(res.products);

        if(res.current_page >= res.max_page) {
            $('.filtry-infinity-load').hide();
        }
    });
}