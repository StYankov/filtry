<?php

namespace Filtry\Core;

use Filtry\Utils\Counter;

class Hooks {
    public function __construct() {
        /** Update Count Matrix when a product is updated */
        add_action( 'save_post_product', [ Counter::class, 'update_count_matrix' ] );
        /** Update Count Matrix when a term is deleted */
        add_action( 'delete_term', [ Counter::class, 'update_count_matrix' ] );

        add_action( 'filtry_update_count_matrix', [ Counter::class, 'update_count_matrix' ] );

        add_action( 'init', [ $this, 'schedule_count_matrix_update' ] );
        
        add_filter( 'rewrite_rules_array', [ $this, 'disable_pretty_pagination' ] );
        add_filter( 'redirect_canonical', [ $this, 'remove_page_number_permalink_redirect'] );

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

    /**
     * Do not redirect to /page/{number} archive when there is a ?paged={number}
     * query param
     * 
     * @param string $redirect_url
     * 
     * @return string|false
     */
    public function remove_page_number_permalink_redirect( $redirect_url ) {
        if( false === ( is_shop() || is_product_taxonomy() ) ) {
            return $redirect_url;
        }

        if( is_paged() && get_query_var( 'paged' ) > 0 ) {
            return false;
        }

        return $redirect_url;
    }
}