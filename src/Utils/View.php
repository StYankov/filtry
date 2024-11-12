<?php

namespace Filtry\Utils;

use Filtry\Admin\Settings;
use Filtry\Core\Filters;
use Filtry\Enums\SettingsEnum;
use Filtry\Utils\Template;

class View {
    public static function render_filters() {
        echo self::get_filters_html();
    }

    public static function get_filters_html() {
        $data = [
            'filters'        => Filters::get_filters_for_display(),
            'active_filters' => Filters::get_activated_filters()
        ];

        return Template::render_html( 'filters.php', $data );
    }

    public static function render_widget( array $args ) {
        $data = wp_parse_args( $args, [
            'title'         => __( 'Filters', 'filtry' ),
            'mobileFilters' => boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS, true ) )
        ] );

        return Template::render( 'widget/widget.php', $data );
    }
}