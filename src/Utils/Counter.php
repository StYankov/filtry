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
        $active_filters = Filters::get_activated_filters();

        if( $active_filters->count() === 0 ) {
            return $term->count;
        }

        $product_ids = self::get_term_cache( $term );

        /**
         * Store available products per taxonomy
         */
        $taxonomy_products = [];

        // Add current taxonomy
        $taxonomy_products[ $term->taxonomy ] = $product_ids;

        foreach( $active_filters as $active_filter ) {
            foreach( $active_filter->active_terms as $term_slug ) {

                // Count is not shown on active terms
                if( $term->slug === $term_slug ) {
                    continue;
                }

                $t = get_term_by( 'slug', $term_slug, $active_filter->id );

                if( empty( $t ) ) {
                    continue;
                }

                $term_products = self::get_term_cache( $t );

                if( empty( $taxonomy_products[ $active_filter->id ] ) ) {
                    $taxonomy_products[ $active_filter->id ] = $term_products;
                    continue;
                }

                
                if( $filter->logic === LogicEnum::OR ) {
                    $taxonomy_products[ $active_filter->id ] = array_unique( array_merge( $taxonomy_products[ $active_filter->id ], $term_products ) );
                } else {
                    $taxonomy_products[ $active_filter->id ] = array_intersect( $taxonomy_products[ $active_filter->id ], $term_products );
                }
            }
        }

        return count( array_intersect( ...array_values( $taxonomy_products ) ) );
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

                /**
                 *  Currently saving the cache per taxonomy not per term
                 *  Might use it in the future and maybe for bigger stores
                 */
                // $key = 'filtry_matrix_' . $filter->id . '_' . $term->term_id;
                
                // update_option( $key, $filter_matrix[ $term->term_id ], false );
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
    public static function get_products_in_term( \WP_Term $term ) {
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
                    'field'     => 'slug',
                    'operator'  => 'NOT IN',
                ],
                [
                    'taxonomy'  => $term->taxonomy,
                    'terms'     => [ $term->term_id ],
                    'field'     => 'term_id',
                    'operator'  => 'IN',
                    'include_children' => false
                ]
            ]
        ] );

        return $query->posts;
    }

    /**
     * @param Term $term
     * 
     * @return int[] Product ids that are in the term
     */
    private static function get_term_cache( Term|\WP_Term $term ) {
        $cache = get_option( 'filtry_matrix_' . $term->taxonomy, [] );

        return isset( $cache[ $term->term_id ] ) ? $cache[ $term->term_id ] : [];
    }
}