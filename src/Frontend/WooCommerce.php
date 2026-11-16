<?php

namespace Filtry\Frontend;

use Filtry\Admin\Settings;
use Filtry\Enums\SettingsEnum;
use Filtry\Utils\Pagination;
use Filtry\Utils\Template;

class WooCommerce {
    public function __construct() {
        $this->maybe_replace_wc_pagination();

        add_filter( 'wc_get_template', [ $this, 'override_result_count_template' ], 10, 2 );
    }

    public function maybe_replace_wc_pagination() {
        if( true === boolval( Settings::get_option( SettingsEnum::INFINITY_LOAD ) ) ) {
            remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );
            add_action( 'woocommerce_after_shop_loop', [ $this, 'render_load_more_button'] );
        }
    }

    public function render_load_more_button() {
        $current_page = Pagination::current_page();

        /**
         * A "previous page" link is only useful on the discrete /page/{n}/ archives.
         * The cumulative ?paged={n} view already lists everything before it.
         */
        $prev_url = $current_page > 1 && ! Pagination::is_cumulative()
            ? Pagination::page_url( $current_page - 1 )
            : null;

        /**
         * Don't show the load more button on the last page
         */
        $has_next = $current_page < Pagination::total_pages();

        if( ! $has_next && null === $prev_url ) {
            return;
        }

        Template::render( 'load-more-button.php', [
            'next_page' => $has_next ? $current_page + 1 : null,
            'next_url'  => $has_next ? Pagination::page_url( $current_page + 1 ) : null,
            'prev_url'  => $prev_url,
        ] );
    }

    public function override_result_count_template( $template, $template_name ) {
        if( $template_name !== 'loop/result-count.php' ) {
            return $template;
        }

        $woocommerce_plugin_path = dirname( WC_PLUGIN_FILE );

        // If the template file is not overriden then load the one from the plugin
        if( strpos( $template, $woocommerce_plugin_path ) !== false ) {
            return Template::locate_template( 'result-count.php' );
        }

        return $template;
    }
}