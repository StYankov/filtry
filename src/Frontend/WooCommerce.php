<?php

namespace Filtry\Frontend;

use Filtry\Admin\Settings;
use Filtry\Enums\SettingsEnum;
use Filtry\Utils\Template;

class WooCommerce {
    public function __construct() {
        $this->add_hooks();
        $this->remove_hooks();
    }
    
    public function add_hooks() {
        add_action( 'woocommerce_after_shop_loop', [ $this, 'render_loader' ] );
    }

    public function remove_hooks() {
        if( true === boolval( Settings::get_option( SettingsEnum::INFINITY_LOAD ) ) ) {
            remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );
            add_action( 'woocommerce_after_shop_loop', [ $this, 'render_load_more_button'] );
        }
    }

    public function render_loader() {
        Template::render( 'loader.php' );
    }

    public function render_load_more_button() {
        Template::render( 'load-more-button.php' );
    }
}