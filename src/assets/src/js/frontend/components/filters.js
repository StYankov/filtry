import { createQueryString, getActiveFilters } from '../utils/filters';
import { getSettings } from '../utils/settings';
import { handleAjaxReload, initAjaxReload } from './ajaxSubmit';
import { initInfinityLoad } from './infinityLoad';

export function initFilters() {
    jQuery(document.body).on('change', '.filtry-toggleable input', filterProducts);
    jQuery(document.body).on('click', '.filtry-reset', resetFilters);
    jQuery(document.body).on('click', '.filtry-submit', filterProducts);
    jQuery(document.body).on('click', '.filtry--collapsable .filtry__head', collapseToggle);

    if(getSettings().ajax === true) {
        initAjaxReload();
    }

    if(getSettings().ajax === true && getSettings().infinity_load === true) {
        initInfinityLoad();
    }
}

function filterProducts() {
    if(!getSettings().autosubmit) {
        return;
    }

    const activefilters = getActiveFilters();

    if('paged' in activefilters) {
        delete activefilters['paged'];
    }

    const queryString = createQueryString(activefilters);

    if(getSettings().ajax === true) {
        handleAjaxReload(queryString);
    } else {
        window.location.href = `${window.location.href.split('?')[0]}?${queryString}`;
    }
}

function resetFilters() {
    const noQueryUrl = window.location.href.split('?')[0];

    window.location.href = noQueryUrl;
}

function collapseToggle() {
    jQuery(this).closest('.filtry--collapsable')
        .toggleClass('filtry--hidden')
        .find('.filtry__list')
        .slideToggle();
}