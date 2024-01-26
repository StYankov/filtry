<?php

namespace Filtry\Admin;

use Filtry\Core\Filters;
use Filtry\Enums\SettingsEnum;

class Data {
    private function __construct() { }

    public static function get_data() {
        return [
            'filters'        => Filters::get_filters(),
            'hideEmpty'      => Settings::get_option( SettingsEnum::HIDE_EMPTY, true ),
            'dynamicRecount' => Settings::get_option( SettingsEnum::DYNAMIC_RECOUNT, true ),
            'showCount'      => Settings::get_option( SettingsEnum::SHOW_COUNT, true ),
            'selectedFirst'  => Settings::get_option( SettingsEnum::SELECTED_FIRST, true ),
            'autosubmit'     => Settings::get_option( SettingsEnum::AUTOSUBMIT, true ),
            'ajax_reload'    => Settings::get_option( SettingsEnum::AJAX_RELOAD, false ),
            'infinity_load'  => Settings::get_option( SettingsEnum::INFINITY_LOAD, false ),
            'enable_loader'  => Settings::get_option( SettingsEnum::ENABLE_LOADER, true ),
            'mobile_filters' => Settings::get_option( SettingsEnum::MOBILE_FILTERS, true )
        ];
    }
}