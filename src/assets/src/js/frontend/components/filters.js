import { createQueryString, getActiveFilters } from '../utils/filters';
import { getSettings } from '../utils/settings';
import { getBaseUrl } from '../utils/router';
import { handleAjaxReload, initAjaxReload } from './ajaxSubmit';
import { initInfinityLoad } from './infinityLoad';

export function initFilters() {
    jQuery(document.body).on('change', '.filtry__toggleable input', onFilterChange);
    jQuery(document.body).on('click', '.filtry__reset', resetFilters);
    jQuery(document.body).on('click', '.filtry__submit', applyFilters);
    jQuery(document.body).on('click', '.filtry__filter--collapsable .filtry__filter-head', collapseToggle);
    jQuery(document.body).on('click', '.filtry__popup-close', popupFiltersToggle);
    jQuery(document.body).on('click', '.filtry__popup-toggle', popupFiltersToggle);
    jQuery(document.body).on('click', '.filtry__popup-back', popupFiltersToggle);
    jQuery(document.body).on('click', '.filtry__mobile-reset', resetFilters);
    jQuery(document.body).on('click', '.filtry__mobile-apply', applyFilters);

    if(getSettings().ajax === true) {
        initAjaxReload();
    }

    if(getSettings().ajax === true && getSettings().infinity_load === true) {
        initInfinityLoad();
    }
}

function applyFilters() {
    // Disable popup in case it's enabled
    document.body.classList.remove('filtry--show-popup');

    const activefilters = getActiveFilters();

    if('paged' in activefilters) {
        delete activefilters['paged'];
    }

    const queryString = createQueryString(activefilters);

    if(getSettings().ajax === true) {
        handleAjaxReload(queryString);
    } else {
        window.location.href = queryString.length > 0 ? `${getBaseUrl()}?${queryString}` : getBaseUrl();
    }
}

function onFilterChange() {
    if(getSettings().autosubmit === false) {
        return;
    }

    /**
     * Autosubmit is always disabled for the mobile menu
     */
    if(768 > window.innerWidth && getSettings().mobile_filters === true) {
        return;
    }

    applyFilters();
}

function resetFilters() {
    const noQueryUrl = window.location.href.split('?')[0];
    const { is_taxonomy_page, shop_page } = getSettings();

    window.location.href = is_taxonomy_page ? shop_page : noQueryUrl;
}

function collapseToggle() {
    jQuery(this).closest('.filtry__filter')
        .toggleClass('filtry__filter--hidden')
        .find('.filtry__list')
        .slideToggle();
}

function popupFiltersToggle() {
    document.body.classList.toggle('filtry--show-popup');
}