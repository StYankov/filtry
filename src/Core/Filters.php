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
use Filtry\Utils\Counter;

class Filters {
    private static $filters = NULL;
    /**
     * @var 
     */
    private static $active_filters = NULL;

    /**
     * Get filters from db and convert them to PHP collection
     * 
     * @return FiltersCollection
     */
    public static function get_filters(): FiltersCollection {
        if( ! empty( self::$filters ) ) {
            return self::$filters;
        }

        $filters = Settings::get_option( SettingsEnum::FILTERS, [] );

        $filters = FiltersCollection::fromArray( $filters );

        if( is_admin() ) {
            $filters = self::merge_with_default( $filters );
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

        $filters        = self::get_filters();
        $active_filters = new FiltersCollection();

        foreach( $filters as $filter ) {
            if( empty( $_REQUEST[ $filter->slug ] ) ) {
                continue;
            }

            $filter->active_terms = explode( ',', $_REQUEST[ $filter->slug ] );

            $active_filters[ $filter->slug ] = $filter;
        }

        if( is_product_taxonomy() ) {
            $term     = get_queried_object();
            $taxonomy = get_taxonomy( $term->taxonomy );

            $slug = isset( $taxonomy->rewrite ) && isset( $taxonomy->rewrite['slug'] ) ? $taxonomy->rewrite['slug'] : $term->taxonomy;

            if( ! empty( $active_filters[ $slug ] ) ) {
                $active_filters[ $slug ]->active_terms[] = $term->slug;
            } else {
                $filter = $filters[ $taxonomy->name ];
                $filter->active_terms = [ $term->slug ];

                $active_filters[ $slug ] = $filter;
            }
        }

        self::$active_filters = $active_filters;

        return self::$active_filters;;
    }

    public static function get_filters_for_display(): FiltersCollection {
        $filters = self::get_filters();

        foreach( $filters as $filter ) {
            if( $filter->type === TypeEnum::TAXONOMY ) {
                $filter->terms = self::get_terms_for_filter( $filter );

                // Hide filters with 0 terms
                if( $filter->terms->count() === 0 ) {
                    $filter->enabled = false;
                }
            }
        }

        return $filters;
    }

    private static function get_terms_for_filter( Filter $filter ): TermCollection {
        $collection = new TermCollection();

        if( false === $filter->enabled ) {
            return $collection;
        }

        $hide_empty      = Settings::get_option( SettingsEnum::HIDE_EMPTY );
        $dynamic_recount = Settings::get_option( SettingsEnum::DYNAMIC_RECOUNT );
        $active_filters  = Filters::get_activated_filters();

        $terms = get_terms( [
            'taxonomy'   => $filter->id,
            'hide_empty' => $hide_empty
        ] );


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

        if( true === boolval( Settings::get_option( SettingsEnum::SELECTED_FIRST ) ) ) {
            $collection->uasort( fn( $a, $b ) => absint( $b->is_selected ) - absint( $a->is_selected ) );
        }

        return $collection;
    }

    private static function merge_with_default( FiltersCollection $filters ): FiltersCollection {
        $default_filters = self::get_default_filters();

        /**
         * Add missing taxonomies to the saved filters collection
         */
        foreach( $default_filters as $filter ) {
            if( empty( $filters[ $filter->id ] ) ) {
                $filters[ $filter->id ] = $filter;
            }
        }

        /**
         * If filter does not exist in the saved filters collection, remove it
         */
        foreach( $filters as $filter ) {
            if( empty( $default_filters[ $filter->id ] ) ) {
                unset( $filters[ $filter->id ] );
            }
        }

        return $filters;
    }
}