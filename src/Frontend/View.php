<?php

namespace Filtry\Frontend;

use Filtry\Admin\Settings;
use Filtry\Core\Filters;
use Filtry\Enums\SettingsEnum;
use Filtry\Utils\Template;

class View {
    public static function display_filters() {
        echo self::get_filters_html();
    }

    public static function get_filters_html() {
        $data = [
            'filters'        => Filters::get_filters_for_display(),
            'active_filters' => Filters::get_activated_filters(),
            'autosubmit'     => Settings::get_option( SettingsEnum::AUTOSUBMIT, true )
        ];

        return Template::render_html( 'filters.php', $data );
    }
}