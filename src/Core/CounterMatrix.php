<?php

namespace Filtry\Core;

use Filtry\Admin\Settings;
use Filtry\Enums\SettingsEnum;

class CounterMatrix {
    /**
     * Update the count matrix for each term
     * This opeartion takes time
     */
    public static function update_count_matrix() {
        $filters = Filters::get_filters();

        $matrix = [];

        foreach( $filters as $filter ) {
            $terms = get_terms( ['taxonomy' => $filter->id, 'hide_empty' => true] );

            $matrix[ $filter->id ] = [];

            $filter_matrix = [];

            foreach( $terms as $term ) {
                $filter_matrix[ $term->term_id ] = self::get_products_in_term( $term );

                $key = 'filtry_matrix_' . $filter->id . '_' . $term->term_id;
                
                update_option( $key, $filter_matrix[ $term->term_id ], false );
            }

            update_option( 'filtry_matrix_' . $filter->id, $filter_matrix );
        }

        Settings::set_option( SettingsEnum::CACHE_MATRIX, $matrix );
    }

    /**
     * Get the product ids that are in the given term
     * 
     * @param \WP_Term $term
     * 
     * @return int[] Product ids
     */
    private static function get_products_in_term( \WP_Term $term ) {
        $query = new \WP_Query( [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'tax_query'      => [
                [
                    'taxonomy'  => 'product_visibility',
                    'terms'     => [ 'exclude-from-catalog' ],
                    'field'     => 'name',
                    'operator'  => 'NOT IN',
                ],
                [
                    'taxonomy'  => $term->taxonomy,
                    'terms'     => [ $term->term_id ],
                    'field'     => 'term_id',
                    'operator'  => 'IN'
                ]
            ]
        ] );

        return $query->posts;
    }
}