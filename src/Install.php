<?php

namespace Filtry;

use Filtry\Admin\Settings;
use Filtry\Core\Filters;
use Filtry\Enums\SettingsEnum;
use Filtry\Utils\Counter;

class Install {
    public static function install() {
		if( empty( Settings::get_option( SettingsEnum::FILTERS ) ) ) {
			Settings::set_option(
				SettingsEnum::FILTERS,
				json_decode( json_encode( Filters::get_default_filters()->getArrayCopy() ), true )
			);
		}

		Counter::update_count_matrix();

		flush_rewrite_rules();
	}
}