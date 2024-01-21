<?php

namespace Filtry\Core;

use Filtry\Dto\Filter;
use Filtry\Dto\Term;
use Filtry\Enums\LogicEnum;

class Counter {
    public static function get_term_count( Term $term, Filter $filter ) {
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
                // $count_matrix = get_option( 'filtry_matrix_' . $t->taxonomy . '_' . $t->term_id, [] );

                $filter_product_ids = array_merge( $filter_product_ids, $count_matrix );
            }

            // if( $term->taxonomy === $filter->id && $filter->logic ) {
            if( $filter->logic === LogicEnum::OR ) {
                $product_ids = array_unique( array_merge( $product_ids, $filter_product_ids ) );
            } else {
                $product_ids = array_intersect( $product_ids, $filter_product_ids );
            }
        }

        return count( $product_ids );
    }
}