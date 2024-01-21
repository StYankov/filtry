<?php

namespace Filtry\Core;

use Filtry\Admin\Settings;
use Filtry\Dto\Filter;
use Filtry\Dto\FiltersCollection;
use Filtry\Dto\Term;
use Filtry\Dto\TermCollection;
use Filtry\Enums\LogicEnum;
use Filtry\Enums\OrderEnum;
use Filtry\Enums\SettingsEnum;
use Filtry\Enums\SortEnum;
use Filtry\Enums\TypeEnum;
use Filtry\Enums\ViewEnum;

class Filters {
    private static $filters = NULL;
    /**
     * @var 
     */
    private static $active_filters = NULL;

    /**
     * Merge default filters with saved filters from the database
     * sorted by order
     * 
     * @return FiltersCollection
     */
    public static function get_filters(): FiltersCollection {
        if( ! empty( self::$filters ) ) {
            return self::$filters;
        }

        $filters = Settings::get_option( SettingsEnum::FILTERS, [] );

        if( empty( $filters ) ) {
            $filters = [];
        }

        $filters = FiltersCollection::fromArray( $filters );

        /**
         * Merge default filters and saved ones.
         * @var Filter $filter
         */
        foreach( self::get_default_filters() as $id => $filter ) {
            if( empty( $filters[ $id ] ) ) {
                $filters[ $id ] = $filter;
                continue;
            }
        }

        $filters->uasort( fn( $a, $b ) => $a['order'] - $b['order'] );

        self::$filters = $filters;

        return self::$filters;
    }

    /**
     * Get default filters from existing product taxonomies
     * 
     * @return FiltersCollection
     */
    public static function get_default_filters(): FiltersCollection {
        $taxonomies = get_object_taxonomies( 'product', 'objects' );

        $hidden_taxonomies = apply_filters( 'filtry_hidden_taxonomies', [ 'product_type', 'product_visibility', 'product_shipping_class' ] );

        foreach( $hidden_taxonomies as $taxonomy ) {
            if( isset( $taxonomies[ $taxonomy ] ) ) {
                unset( $taxonomies[ $taxonomy ] );
            }
        }

        $filters = new FiltersCollection();

        foreach( array_values( $taxonomies ) as $i => $taxonomy ) {
            if( is_array( $taxonomy->rewrite ) && isset( $taxonomy->rewrite['slug'] ) ) {
                $slug = $taxonomy->rewrite['slug'];
            } else {
                $slug = $taxonomy->name;
            }

            $filters[ $taxonomy->name ] = new Filter(
                enabled: true,
                id: $taxonomy->name,
                slug: $slug,
                order: $i,
                label: $taxonomy->label,
                type: TypeEnum::TAXONOMY,
                view: ViewEnum::CHECKBOX,
                collapsable: true,
                logic: LogicEnum::OR,
                sort: SortEnum::COUNT,
                sortOrder: OrderEnum::DESC
            );
        }

        return $filters;
    }

    /**
     * Get filters that are active as of the current URL query
     * 
     * @return FiltersCollection
     */
    public static function get_activated_filters(): FiltersCollection {
        if( ! empty( self::$active_filters ) ) {
            return self::$active_filters;
        }

        $active_filters = new FiltersCollection();

        foreach( self::get_filters() as $filter ) {
            if( empty( $_REQUEST[ $filter->slug ] ) ) {
                continue;
            }

            $filter->active_terms = explode( ',', $_REQUEST[ $filter->slug ] );

            $active_filters[ $filter->slug ] = $filter;
        }

        self::$active_filters = $active_filters;

        return self::$active_filters;;
    }

    public static function get_filters_for_display(): FiltersCollection {
        $filters = self::get_filters();

        foreach( $filters as $filter ) {
            $filter->terms = self::get_terms_for_filter( $filter );
        }

        return $filters;
    }

    private static function get_terms_for_filter( Filter $filter ): TermCollection {
        if( false === $filter->enabled ) {
            return [];
        }

        $hide_empty      = Settings::get_option( SettingsEnum::HIDE_EMPTY, true );
        $dynamic_recount = Settings::get_option( SettingsEnum::DYNAMIC_RECOUNT, true );
        $active_filters  = Filters::get_activated_filters();

        $terms = get_terms( [
            'taxonomy'   => $filter->id,
            'hide_empty' => $hide_empty
        ] );

        $collection = new TermCollection();

        foreach( $terms as $term ) {
            $t = new Term(
                term_id: $term->term_id,
                taxonomy: $term->taxonomy,
                slug: $term->slug,
                name: $term->name,
                count: $term->count
            );

            if( $dynamic_recount ) {
                $t->count = Counter::get_term_count( $t, $filter );
            }

            if( $hide_empty && $t->count === 0 ) {
                continue;
            }

            if( isset( $active_filters[ $filter->slug ] ) && in_array( $term->slug, $active_filters[ $filter->slug ]->active_terms ) ) {
                $t->is_selected = true;
            }

            $collection[] = $t;
        }

        $collection->uasort( function( $a, $b ) use ( $filter ) {
            $cmp = 0;
            if( $filter->sort === SortEnum::ID ) {
                $cmp = $a->term_id - $b->term_id;
            } else if( $filter->sort === SortEnum::COUNT ) {
                $cmp = $a->count - $b->count;
            } else {
                $cmp = strcmp( $a->name, $b->name );
            }

            if( $filter->sortOrder === OrderEnum::DESC ) {
                $cmp = $cmp * -1;
            }

            return $cmp;
        } );

        if( Settings::get_option( SettingsEnum::SELECTED_FIRST, true ) ) {
            $collection->uasort( fn( $a, $b ) => absint( $b->is_selected ) - absint( $a->is_selected ) );
        }

        return $collection;
    }
}