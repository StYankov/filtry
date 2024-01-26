<?php

namespace Filtry\Core;

use Filtry\Utils\Counter;

class Hooks {
    public function __construct() {
        /** Update Count Matrix when a product is updated */
        add_action( 'save_post_product', [ Counter::class, 'update_count_matrix' ] );
        /** Update Count Matrix when a term is deleted */
        add_action( 'delete_term', [ Counter::class, 'update_count_matrix' ] );

        add_action( 'init', [ $this, 'schedule_count_matrix_update' ] );
        

        add_filter( 'rewrite_rules_array', [ $this, 'disable_pretty_pagination' ] );

        add_action( 'filtry_update_count_matrix', [ Counter::class, 'update_count_matrix' ] );
    }

    public function schedule_count_matrix_update() {
        if( ! wp_next_scheduled( 'filtry_update_count_matrix' ) ) {
            wp_schedule_event( time(), 'daily', 'filtry_update_count_matrix' );
        }
    }

    public function disable_pretty_pagination( $rules ) {
        foreach( $rules as $rule => $rewrite ) {
            if( strpos( $rewrite, 'paged=' ) ) {
                unset( $rules[ $rule ] );
            }
        }
        
        return $rules;
    }
}