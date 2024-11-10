<?php

namespace Filtry\Enums;

enum SettingsEnum: string {
    case FILTERS         = 'filters';
    case HIDE_EMPTY      = 'hide_empty';
    case SHOW_COUNT      = 'show_count';
    case DYNAMIC_RECOUNT = 'dynamic_recount';
    case CACHE_MATRIX    = 'cache_matrix';
    case SELECTED_FIRST  = 'selected_first';
    case AUTOSUBMIT      = 'autosubmit';
    case AJAX_RELOAD     = 'ajax_reload';
    case INFINITY_LOAD   = 'infinity_load';
    case ENABLE_LOADER   = 'enable_loader';
    case MOBILE_FILTERS  = 'mobile_filters';
}