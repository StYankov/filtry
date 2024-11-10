<?php

namespace Filtry\Admin;

use Filtry\Core\Filters;
use Filtry\Enums\DesignSettingsEnum;
use Filtry\Enums\SettingsEnum;

class Data {
    private function __construct() { }

    public static function get_data() {
        return [
            'filters'        => Filters::get_filters(),
            'hideEmpty'      => Settings::get_option( SettingsEnum::HIDE_EMPTY ),
            'dynamicRecount' => Settings::get_option( SettingsEnum::DYNAMIC_RECOUNT ),
            'showCount'      => Settings::get_option( SettingsEnum::SHOW_COUNT ),
            'selectedFirst'  => Settings::get_option( SettingsEnum::SELECTED_FIRST ),
            'autosubmit'     => Settings::get_option( SettingsEnum::AUTOSUBMIT ),
            'ajax_reload'    => Settings::get_option( SettingsEnum::AJAX_RELOAD ),
            'infinity_load'  => Settings::get_option( SettingsEnum::INFINITY_LOAD ),
            'enable_loader'  => Settings::get_option( SettingsEnum::ENABLE_LOADER ),
            'mobile_filters' => Settings::get_option( SettingsEnum::MOBILE_FILTERS ),
            'design'         => [
                'disable'                  => Settings::get_option( DesignSettingsEnum::DISABLE_STYLES ) === '1',
                'primary_color'            => Settings::get_option( DesignSettingsEnum::PRIMART_COLOR, '#F3F8FF' ),
                'secondary_color'          => Settings::get_option( DesignSettingsEnum::SECONDARY_COLOR, '#07090F' ),
                'accent_color'             => Settings::get_option( DesignSettingsEnum::ACCENT_COLOR, '#273469' ),
                'floating_button_position' => Settings::get_option( DesignSettingsEnum::FLOATING_BUTTON_POSITION, 'none' ),
            ]
        ];
    }
}