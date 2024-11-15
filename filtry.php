<?php
/**
 * Plugin Name: Filtry
 * Plugin URI: https://filtry.stoilyankov.com/
 * Description: Lightning-fast WooCommerce filters that make shopping a breeze! More finds, more sales, less fuss!
 * Version: 0.85
 * Author: Stoil Yankov
 * Author URI: https://stoilyankov.com
 * Text Domain: filtry
 * Domain Path: /languages/
 * Requires at least: 6.2
 * Requires PHP: 8.1
 * License: GPL2
 */

defined( 'ABSPATH' ) || exit;
 
define( 'FILTRY_PLUGIN_FILE', __FILE__ );

require_once __DIR__ . '/vendor/autoload.php';

function filtry() {
    return \Filtry\Filtry::instance();
}

$GLOBALS['filtry'] = filtry();

register_activation_hook( __FILE__, [ \Filtry\Install::class, 'install' ] );
