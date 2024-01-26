<?php

namespace Filtry\Frontend;

use Filtry\Admin\Settings;
use Filtry\Enums\SettingsEnum;
use Filtry\Filtry;

class Enqueue {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue'] );
    }

    public function enqueue() {
        wp_register_script( 'filtry-frontend', Filtry::plugin_url() . '/src/assets/dist/frontend/base.js', ['jquery'], Filtry::instance()->version, true );
        wp_add_inline_script( 'filtry-frontend', 'window.filtrySettings = ' . json_encode( $this->script_data() ), 'before' );

        wp_register_style( 'filtry-frontend', Filtry::plugin_url() . '/src/assets/dist/frontend/style.css' );

        // If the page is not part of woocommerce store don't enqueue anything
        if( ! ( is_shop() || is_product_taxonomy() || is_search() ) ) {
            return;
        }

        wp_enqueue_script( 'filtry-frontend' );

        if( false === apply_filters( 'filtry_unstyled', false ) ) {
            wp_enqueue_style( 'filtry-frontend' );
        }
    }

    public function script_data() {
        return [
            'autosubmit'     => boolval( Settings::get_option( SettingsEnum::AUTOSUBMIT ) ),
            'ajax'           => boolval( Settings::get_option( SettingsEnum::AJAX_RELOAD ) ),
            'infinity_load'  => boolval( Settings::get_option( SettingsEnum::INFINITY_LOAD ) ),
            'mobile_filters' => boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS ) ),
            'rest_nonce'     => wp_create_nonce( 'wp_rest' ),
            'rest_url'       => rest_url( 'filtry/v1/shop' )
        ];
    }
}