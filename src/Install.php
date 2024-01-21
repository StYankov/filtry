<?php

namespace Filtry;

use Filtry\Admin\Settings;
use Filtry\Core\Filters;
use Filtry\Enums\SettingsEnum;

class Install {
    public static function install() {
		if( empty( Settings::get_option( SettingsEnum::FILTERS ) ) ) {
			update_option( Settings::SETTINGS_PREFIX . '_' . SettingsEnum::FILTERS->value, Filters::get_default_filters(), false );
		}
	}
}