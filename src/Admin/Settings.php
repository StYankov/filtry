<?php

namespace Filtry\Admin;

use Filtry\Enums\DesignSettingsEnum;
use Filtry\Enums\SettingsEnum;
use Filtry\Filtry;

class Settings {
    protected $settings = [];

    protected $settings_group  = 'filtry_settings';
    const SETTINGS_PREFIX      = 'filtry';

    public function __construct() {
        $this->settings = [
            [
                'id'      => SettingsEnum::FILTERS->value,
                'default' => [],
                'type'    => 'object',
                'show_in_rest' => [
                    'schema' => [
                        'type'                 => 'object',
                        'additionalProperties' => true
                    ]
                ]
            ],
            [
                'id'           => SettingsEnum::SHOW_COUNT->value,
                'default'      => true,
                'type'         => 'boolean',
            ],
            [
                'id'           => SettingsEnum::DYNAMIC_RECOUNT->value,
                'default'      => true,
                'type'         => 'boolean',
            ],
            [
                'id'           => SettingsEnum::HIDE_EMPTY->value,
                'default'      => true,
                'type'         => 'boolean',
            ],
            [
                'id'           => SettingsEnum::SELECTED_FIRST->value,
                'default'      => true,
                'type'         => 'boolean' 
            ],
            [
                'id'           => SettingsEnum::AUTOSUBMIT->value,
                'default'      => false,
                'type'         => 'boolean'
            ],
            [
                'id'           => SettingsEnum::AJAX_RELOAD->value,
                'default'      => false,
                'type'         => 'boolean'
            ],
            [
                'id'           => SettingsEnum::INFINITY_LOAD->value,
                'default'      => false,
                'type'         => 'boolean'
            ],
            [
                'id'           => SettingsEnum::ENABLE_LOADER->value,
                'default'      => true,
                'type'         => 'boolean'
            ],
            [
                'id'           => SettingsEnum::MOBILE_FILTERS->value,
                'default'      => true,
                'type'         => 'boolean'
            ],
            [
                'id'           => DesignSettingsEnum::DISABLE_STYLES->value,
                'default'      => false,
                'type'         => 'boolean'
            ],
            [
                'id'           => DesignSettingsEnum::PRIMART_COLOR->value,
                'default'      => '#F3F8FF',
                'type'         => 'string'
            ],
            [
                'id'           => DesignSettingsEnum::SECONDARY_COLOR->value,
                'default'      => '#07090F',
                'type'         => 'string'
            ],
            [
                'id'           => DesignSettingsEnum::ACCENT_COLOR->value,
                'default'      => '#273469',
                'type'         => 'string'
            ],
            [
                'id'           => DesignSettingsEnum::FLOATING_BUTTON_POSITION->value,
                'default'      => 'none',
                'type'         => 'string'
            ]
        ];

        add_action( 'init', [ $this, 'register' ] );
        add_action( 'admin_menu', [ $this, 'settings_page' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
    }

    public function register() {
        foreach( $this->settings as $setting ) {
            register_setting(
                $this->settings_group,
                self::SETTINGS_PREFIX . '_' . $setting['id'],
                [
                    'type'         => $setting['type'],
                    'default'      => $setting['default'],
                    'show_in_rest' => isset( $setting['show_in_rest'] ) ? $setting['show_in_rest'] : true 
                ]
            );
        }
    }

    public function settings_page() {
        add_submenu_page(
            'woocommerce',
            __( 'Filtry', 'filtry' ),
            __( 'Filtry', 'filtry' ),
            'manage_woocommerce',
            'filtry-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    public function render_settings_page() {
        echo '<div id="filtry-settings"></div>';
    }

    public function enqueue() {
        wp_enqueue_script( 'filtry-settings', Filtry::plugin_url( 'src/assets/dist/settings/base.js' ), [ 'react', 'react-dom', 'wp-api', 'wp-data', 'wp-notices', 'wp-components' ], '1.0', true );
        wp_add_inline_script( 'filtry-settings', 'window.filtersSettings = ' . json_encode( Data::get_data() ), 'before' );
        
        wp_enqueue_style( 'wp-components' );
    }

    public static function get_option( string|SettingsEnum|DesignSettingsEnum $key, mixed $default = false ): mixed {
        if( $key instanceof SettingsEnum || $key instanceof DesignSettingsEnum ) {
            $key = $key->value;
        }

        return get_option( self::SETTINGS_PREFIX . '_' . $key, $default );
    }

    public static function set_option( string|SettingsEnum $key, mixed $value, bool $autoload = false ): bool {
        if( $key instanceof SettingsEnum ) {
            $key = $key->value;
        }

        return update_option( self::SETTINGS_PREFIX . '_' . $key, $value, $autoload );
    }

    public function get_settings(): array {
        return $this->settings;
    }
}