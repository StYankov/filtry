<?php

namespace Filtry\Frontend;

use Filtry\Admin\Settings;
use Filtry\Enums\DesignSettingsEnum;
use Filtry\Enums\SettingsEnum;
use Filtry\Filtry;

class Enqueue {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue'] );
    }

    public function enqueue() {
        $version = Filtry::instance()->version;

        wp_register_script( 'filtry-frontend', Filtry::plugin_url() . '/src/assets/dist/frontend/base.js', ['jquery'], $version, true );
        wp_add_inline_script( 'filtry-frontend', 'window.filtrySettings = ' . json_encode( $this->script_data() ), 'before' );

        wp_register_style( 'filtry-frontend', Filtry::plugin_url() . '/src/assets/dist/frontend/style.css', [], $version );

        // If the page is not part of woocommerce store don't enqueue anything
        if( ! ( is_shop() || is_product_taxonomy() || is_search() ) ) {
            return;
        }

        wp_enqueue_script( 'filtry-frontend' );

        if( boolval( Settings::get_option( DesignSettingsEnum::DISABLE_STYLES ) ) === false ) {
            wp_enqueue_style( 'filtry-frontend' );
            wp_add_inline_style( 'filtry-frontend', $this->css_variables() );
        }
    }

    public function script_data() {
        $is_product_taxonomy = is_product_taxonomy() && ! is_post_type_archive();
        $object              = get_queried_object();
        $taxonomy            = is_a( $object, 'WP_Term' ) ? get_taxonomy( $object->taxonomy ) : null;
        $taxonomy_slug       = $taxonomy && $taxonomy->rewrite && isset( $taxonomy->rewrite['slug'] ) ? $taxonomy->rewrite['slug'] : null;

        return [
            'autosubmit'       => boolval( Settings::get_option( SettingsEnum::AUTOSUBMIT ) ),
            'ajax'             => boolval( Settings::get_option( SettingsEnum::AJAX_RELOAD ) ),
            'infinity_load'    => boolval( Settings::get_option( SettingsEnum::INFINITY_LOAD ) ),
            'mobile_filters'   => boolval( Settings::get_option( SettingsEnum::MOBILE_FILTERS ) ),
            'rest_nonce'       => wp_create_nonce( 'wp_rest' ),
            'rest_url'         => rest_url( 'filtry/v1/shop' ),
            'is_taxonomy_page' => $is_product_taxonomy,
            'shop_page'        => get_permalink( wc_get_page_id( 'shop' ) ),
            'taxonomy'         => $taxonomy_slug,
            'term_slug'        => is_a( $object, 'WP_Term' ) ? $object->slug : null,
            'design'           => [
                'disable' => boolval( Settings::get_option( DesignSettingsEnum::DISABLE_STYLES ) )
            ]
        ];
    }

    public function css_variables() {
        $primary_color   = Settings::get_option( DesignSettingsEnum::PRIMART_COLOR, '#F3F8FF' );
        $secondary_color = Settings::get_option( DesignSettingsEnum::SECONDARY_COLOR, '#07090F' );
        $accent_color    = Settings::get_option( DesignSettingsEnum::ACCENT_COLOR, '#273469' );

        return "
            :root {
                --filtry-primary-color: {$primary_color};
                --filtry-secondary-color: {$secondary_color};
                --filtry-accent-color: {$accent_color};
            }
        ";
    }
}