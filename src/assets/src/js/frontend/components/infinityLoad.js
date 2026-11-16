import { createQueryString, getActiveFilters } from '../utils/filters';
import { getShop } from '../utils/ajax';
import { getPageUrl } from '../utils/router';
import { getSettings } from '../utils/settings';

export function initInfinityLoad() {
    $(document.body).on('click', '.filtry-load-more', onNextPage);
}

/**
 * The load more button is a link to the next /page/{n}/ archive.
 * Load the page in place instead of following it.
 *
 * @param {Event} e
 */
function onNextPage(e) {
    e.preventDefault();

    const activeFilters = getActiveFilters();

    activeFilters['paged'] = getNextPage(e.currentTarget, activeFilters);

    const queryString = createQueryString(activeFilters);

    handleInfinityLoad(queryString);
}

/**
 * @param {HTMLElement} button
 * @param {Object} activeFilters
 *
 * @return {number}
 */
function getNextPage(button, activeFilters) {
    const nextPage = parseInt(button.dataset.nextPage);

    if(!Number.isNaN(nextPage) && nextPage > 1) {
        return nextPage;
    }

    if(activeFilters['paged']) {
        return parseInt(activeFilters['paged']) + 1;
    }

    return (parseInt(getSettings().current_page) || 1) + 1;
}

/**
 * Point the load more button to the page after the given one
 *
 * @param {number} currentPage The last loaded page
 */
export function setLoadedPage(currentPage) {
    const nextPage = (parseInt(currentPage) || 1) + 1;

    $('.filtry-load-more').each((i, el) => {
        el.dataset.nextPage = nextPage;

        if(el.hasAttribute('href')) {
            el.setAttribute('href', getPageUrl(nextPage));
        }
    });
}

/**
 * @param {string} queryString
 */
export function handleInfinityLoad(queryString) {
    getShop(queryString, (res) => {
        const $productsList = $('ul.products');
        $productsList.append(res.products);

        setLoadedPage(res.current_page);

        if(res.current_page >= res.max_page) {
            $('.filtry-infinity-load').hide();
        }
    });
}
