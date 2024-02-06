<?php

namespace Filtry\Frontend;

use Filtry\Admin\Settings;
use Filtry\Core\Filters;
use Filtry\Enums\SettingsEnum;
use Filtry\Utils\Template;

class TemplateHooks {
    public function __construct() {
        add_action( 'filtry_pre_filters', [ $this, 'render_sort_filter' ] );
        add_action( 'filtry_after_filters', [ $this, 'render_submit_button' ] );
        add_action( 'filtry_after_filters', [ $this, 'render_reset_button' ], 20 );
        
        add_action( 'filtry_widget_footer', [ $this, 'render_popup_controls' ] );
    }

    /**
     * Render sort options in mobile menu
     */
    public function render_sort_filter() {
        if( false === boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS ) ) ) {
            return;
        }

        Template::render( 'filters/sort-options.php' );
    }

    /**
     * Render reset button if there are any activeted filters
     */
    public function render_reset_button() {
        $active_filters = Filters::get_activated_filters();

        if( $active_filters->count() === 0 ) {
            return;
        }

        Template::render( 'reset-button.php' );
    }

    /**
     * Render popup controls if mobile menu is enabled
     */
    public function render_popup_controls() {
        if( false === boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS ) ) ) {
            return;
        }

        Template::render( 'popup-controls.php' );
    }

    public function render_submit_button() {
        if( boolval( Settings::get_option( SettingsEnum::AUTOSUBMIT ) ) ) {
            return;
        }

        Template::render( 'submit-button.php' );
    }
    
}