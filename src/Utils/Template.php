<?php

namespace Filtry\Utils;

use Filtry\Filtry;

class Template {
    /**
     * @param string $template
     * @param array $data
     * 
     * @return void
     */
    public static function render( $template, $data = [] ) {
        $template_path = self::locate_template( $template );

        if( false === $template_path ) {
            return;
        }

        if( ! empty( $data ) ) {
            extract( $data );
        }

        require $template_path; 
    }

    /**
     * @param string $template
     * @param array $data
     * 
     * @return string
     */
    public static function render_html( $template, $data = [] ) {
        ob_start();

        self::render( $template, $data );

        return ob_get_clean();
    }

    /**
     * Locate a template and return the path.
     *
     * @param string $template_name The name of the template file.
     * @return string|false The path to the template file.
     */
    public static function locate_template( $template_name ) {
        // Define the path in the child theme
        $child_theme_path = get_stylesheet_directory() . '/filtry/' . $template_name;

        // Define the path in the parent theme
        $parent_theme_path = get_template_directory() . '/filtry/' . $template_name;

        // Define the default path in the plugin
        $plugin_path = Filtry::plugin_path() . 'src/templates/' . $template_name;

        // Check if the template exists in the child theme
        if( file_exists( $child_theme_path ) ) {
            return $child_theme_path;
        }

        // Check if the template exists in the parent theme
        if( file_exists( $parent_theme_path ) ) {
            return $parent_theme_path;
        }

        if( file_exists( $plugin_path ) ) {
            return $plugin_path;
        }

        return false;
    }
}