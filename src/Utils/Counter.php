<?php

namespace Filtry\Utils;

use Filtry\Admin\Settings;
use Filtry\Core\Filters;
use Filtry\Dto\Filter;
use Filtry\Dto\Term;
use Filtry\Enums\LogicEnum;
use Filtry\Enums\SettingsEnum;

class Counter {
    /**
     * Retrieve the count of products in the term in relation
     * to the applied filters
     * 
     * @param Term $term
     * @param Filter $filter
     * 
     * @return int
     */
    public static function get_term_count( Term $term, Filter $filter ): int {
        $filters = Filters::get_activated_filters();

        if( empty( $filters ) ) {
            return $term->count;
        }

        $filter_matrix = get_option( 'filtry_matrix_' . $term->taxonomy, [] );
        $product_ids   = isset( $filter_matrix[ $term->term_id ] ) ? $filter_matrix[ $term->term_id ] : []; 

        foreach( $filters as $filter ) {
            $filter_product_ids = [];

            foreach( $filter->active_terms as $slug ) {
                $t = get_term_by( 'slug', $slug, $filter->id );

                if( empty( $t ) ) {
                    continue;
                }

                $filter_matrix = get_option( 'filtry_matrix_' . $t->taxonomy, [] );
                $count_matrix  = isset( $filter_matrix[ $t->term_id ] ) ? $filter_matrix[ $t->term_id ] : []; 

                // Change condition here
                if( $filter->logic === LogicEnum::OR ) {
                    $filter_product_ids = array_merge( $filter_product_ids, $count_matrix );
                } else {
                    $filter_product_ids = array_intersect( $product_ids, $count_matrix );
                }
            }

            $product_ids = array_intersect( $product_ids, array_unique( $filter_product_ids ) );
        }

        return count( $product_ids );
    }

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