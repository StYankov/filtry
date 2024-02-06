<?php

namespace Filtry;

use Filtry\Admin\Settings;

final class Filtry {
	public string $version = '0.6';

    /**
	 * The single instance of the class.
	 *
	 * @var Filtry
	 */
	private static $_instance = null;

	public ?Settings $settings = null;
	
    /**
	 * Main Filtry Instance.
	 *
	 * Ensures only one instance of Filtry is loaded or can be loaded.
	 *
	 * @return self - Main Filtry Instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		$this->init();

        add_action( 'plugins_loaded', [$this, 'load_plugin_textdomain'] );
	}

	public function init() {
		$this->settings = new Settings();
		
		new Core\Bootstrap();
		new Frontend\Bootstrap();
	}

	public function  load_plugin_textdomain() {
        load_plugin_textdomain( 'filtry', false, basename( self::plugin_path() ) . '/languages' );
	}

	public static function plugin_path() {
        return plugin_dir_path( FILTRY_PLUGIN_FILE );
    }

    public static function plugin_url( $path = '' ) {
        return plugins_url( '/' . untrailingslashit( $path ) , FILTRY_PLUGIN_FILE );
    }
}
