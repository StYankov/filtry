<?php

namespace Filtry\Core;

use Filtry\Dto\Filter;
use Filtry\Enums\LogicEnum;
use Filtry\Enums\TypeEnum;

class Query {
    public function __construct() {
        add_action( 'woocommerce_product_query', [ $this, 'query' ] );
    }

    /**
     * Apply filters to the woocommerce query
	 *
	 * @param \WP_Query $query Query instance.
	 */
    public function query( \WP_Query $query ): void {
        foreach( Filters::get_filters() as $filter ) {
            if( empty( $_REQUEST[ $filter->slug ] ) ) {
                continue;
            }

            if( $filter->type === TypeEnum::TAXONOMY ) {
                $this->apply_taxonomy_filter( $query, $filter );
            } else {
                $this->apply_price_filter( $query, $filter );
            }
        }
    }
    
    /**
	 * @param \WP_Query $query Query instance.
     * @param Filter $filter
     */
    public function apply_taxonomy_filter( \WP_Query $query, Filter $filter ): void {
        $values = explode( ',', $_REQUEST[ $filter->slug ] );

        $tax_query = $query->get( 'tax_query', [] );

        if( $filter->logic === LogicEnum::OR ) {
            $tax_query[] = [
                'taxonomy' => $filter->id,
                'field'    => 'slug',
                'terms'    => $values
            ];
        } else {
            foreach( $values as $value ) {
                $tax_query[] = [
                    'taxonomy' => $filter->id,
                    'field'    => 'slug',
                    'terms'    => $value
                ];
            }
        }

        $query->set( 'tax_query', $tax_query );
    }

    /**
	 * @param \WP_Query $query Query instance.
     * @param Filter $filter
     */
    public function apply_price_filter( \WP_Query $query, Filter $filter ) {
        
    }
}