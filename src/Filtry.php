<?php

namespace Filtry;

final class Filtry {
	public string $version = '0.1';

    /**
	 * The single instance of the class.
	 *
	 * @var Filtry
	 */
	protected static $_instance = null;

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
	}

	public function init() {
		new Admin\Bootstrap();
		new Core\Bootstrap();
		new Frontend\Bootstrap();
	}

	public static function get_text_domain() {
		return 'filtry';
	}

	public static function plugin_path() {
        return plugin_dir_path( FILTRY_PLUGIN_FILE );
    }

    public static function plugin_url( $path = '' ) {
        return plugins_url( '/' . untrailingslashit( $path ) , FILTRY_PLUGIN_FILE );
    }
}