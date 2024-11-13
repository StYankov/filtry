<?php

namespace Filtry\Frontend;

use Filtry\Admin\Settings;
use Filtry\Core\Filters;
use Filtry\Enums\SettingsEnum;
use Filtry\Utils\Template;

class TemplateHooks {
    public function __construct() {
        add_action( 'filtry_pre_filters',  [ TemplateHooks::class, 'render_sort_filter' ] );
        add_action( 'filtry_after_filters', [ TemplateHooks::class, 'render_filter_widget_footer_wrapper_open' ] );
        add_action( 'filtry_after_filters', [ TemplateHooks::class, 'render_submit_button' ] );
        add_action( 'filtry_after_filters', [ TemplateHooks::class, 'render_reset_button' ], 20 );
        add_action( 'filtry_after_filters', [ TemplateHooks::class, 'render_filter_widget_footer_wrapper_close' ], 30 );
        
        add_action( 'filtry_widget_footer', [ TemplateHooks::class, 'render_popup_controls' ] );

        add_action( 'woocommerce_before_shop_loop', [ TemplateHooks::class, 'render_mobile_filters_open_button' ], 30 );
        add_action( 'woocommerce_after_shop_loop', [ TemplateHooks::class, 'render_loader' ] ); 
        add_action( 'woocommerce_after_shop_loop', [ TemplateHooks::class, 'render_popup_toggle' ] ); 
    }

    public static function render_sort_filter() {
        if( false === boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS ) ) ) {
            return;
        }

        Template::render( 'filters/sort-options.php' );
    }

    public static function render_filter_widget_footer_wrapper_open() {
        Template::render( 'widget/widget-footer-wrapper-open.php' );
    }

    /**
     * Render reset button if there are any activeted filters
     */
    public static function render_reset_button() {
        $active_filters = Filters::get_activated_filters();

        if( $active_filters->count() === 0 && empty( get_query_var( 's' ) ) ) {
            return;
        }

        Template::render( 'reset-button.php' );
    }

    /**
     * Render popup controls if mobile menu is enabled
     */
    public static function render_popup_controls() {
        if( false === boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS ) ) ) {
            return;
        }

        Template::render( 'popup-controls.php' );
    }

    public static function render_submit_button() {
        if( boolval( Settings::get_option( SettingsEnum::AUTOSUBMIT ) ) ) {
            return;
        }

        Template::render( 'submit-button.php' );
    }

    public static function render_filter_widget_footer_wrapper_close() {
        Template::render( 'widget/widget-footer-wrapper-close.php' );
    }

    public static function render_mobile_filters_open_button() {
        if( boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS, true ) ) === true ) {
            Template::render( 'mobile-filters-open-button.php' );
        }
    }

    public static function render_loader() {
        Template::render( 'loader.php' );
    }

    public static function render_popup_toggle() {
        if( boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS, true ) ) === true ) {
            Template::render( 'popup-toggle.php' );
        }
    }
    
}