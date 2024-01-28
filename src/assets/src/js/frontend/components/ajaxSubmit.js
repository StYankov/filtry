import { getActiveFilters, createQueryString } from '../utils/filters';
import { getShop } from '../utils/ajax';

/**
 * @param {string} queryString
 * @param {function|null} cb
 */
export function handleAjaxReload(queryString) {
    getShop(queryString, (res => {
        const $productsList = jQuery('ul.products');

        $productsList.html(res.products);
        jQuery('.woocommerce-result-count').replaceWith(res.result_count);
        jQuery('.woocommerce-pagination').replaceWith(res.pagination);
        
        if(res.max_page === 1) {
            jQuery('.filtry-infinity-load').hide();
        } else {
            jQuery('.filtry-infinity-load').show();
        }

        window.scrollTo({ top: 100, behavior: 'smooth' });
    }));
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