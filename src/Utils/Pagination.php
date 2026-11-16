<?php

namespace Filtry\Utils;

/**
 * Helpers for the product archive pagination.
 *
 * Two URL forms exist side by side:
 *  - /page/{n}/   a discrete page with only the products of page {n} (crawlable).
 *  - ?paged={n}   a cumulative view of pages 1..{n}, used to restore the list
 *                 after "Show more" (see Core\Query).
 */
class Pagination {
    /**
     * Products per page as configured in WooCommerce
     */
    public static function per_page(): int {
        return absint(
            apply_filters( 'loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page() )
        );
    }

    /**
     * Whether the request is the cumulative ?paged={n} view
     */
    public static function is_cumulative(): bool {
        return isset( $_GET['paged'] ) && absint( $_GET['paged'] ) > 0;
    }

    /**
     * The page the visitor is on. For the cumulative view this is the last loaded page.
     */
    public static function current_page(): int {
        if( self::is_cumulative() ) {
            return absint( $_GET['paged'] );
        }

        return max( 1, absint( get_query_var( 'paged' ) ) );
    }

    /**
     * Number of pages of the main query. `max_num_pages` cannot be used because
     * the cumulative view inflates `posts_per_page`.
     */
    public static function total_pages(): int {
        global $wp_query;

        $per_page = self::per_page();

        if( $per_page < 1 || empty( $wp_query ) ) {
            return 1;
        }

        return max( 1, (int) ceil( (int) $wp_query->found_posts / $per_page ) );
    }

    /**
     * Discrete (/page/{n}/) URL of a page of the current archive. Keeps filter query args.
     */
    public static function page_url( int $page ): string {
        return get_pagenum_link( max( 1, $page ), false );
    }

    /**
     * First page URL of the current archive without a query string
     */
    public static function base_url(): string {
        return trailingslashit( strtok( self::page_url( 1 ), '?' ) );
    }
}
