<?php
/**
 * Plugin Name: Filtry
 * Plugin URI: https://filtry.stoilyankov.com/
 * Description: WooCommerce Product Filters
 * Version: 0.7.3
 * Author: Stoil Yankov
 * Author URI: https://stoilyankov.com
 * Text Domain: filtry
 * Domain Path: /languages/
 * Requires at least: 6.2
 * Requires PHP: 8.1
 *
 */

defined( 'ABSPATH' ) || exit;
 
define( 'FILTRY_PLUGIN_FILE', __FILE__ );

require_once __DIR__ . '/vendor/autoload.php';

function filtry() {
    return \Filtry\Filtry::instance();
}

$GLOBALS['filtry'] = filtry();

register_activation_hook( __FILE__, [\Filtry\Install::class, 'install'] );
