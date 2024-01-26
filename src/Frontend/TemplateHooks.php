<?php

namespace Filtry\Frontend;

use Filtry\Admin\Settings;
use Filtry\Core\Filters;
use Filtry\Enums\SettingsEnum;
use Filtry\Utils\Template;

class TemplateHooks {
    public function __construct() {
        add_action( 'filtry_after_filters', [ $this, 'render_submit_button' ] );
        add_action( 'filtry_after_filters', [ $this, 'render_reset_button' ], 20 );
        
        add_action( 'filtry_widget_footer', [ $this, 'render_popup_controls' ] );
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
        if( false === boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS, true ) ) ) {
            return;
        }

        Template::render( 'popup-controls.php' );
    }

    public function render_submit_button() {
        if( boolval( Settings::get_option( SettingsEnum::AUTOSUBMIT, true ) ) ) {
            return;
        }

        Template::render( 'submit-button.php' );
    }
    
}