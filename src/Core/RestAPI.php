<?php

namespace Filtry\Core;

use Filtry\Utils\View;

class RestAPI {
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    public function register_routes() {
        register_rest_route( 'filtry/v1', 'shop', [
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => [$this, 'get_items'],
            'permission_callback' => [$this, 'verify_nonce'] 
        ] );
    }

    public function get_items( \WP_REST_Request $request ) {
        $query = $this->create_query( $request );
        $query->get_posts();

        ob_start();
        while( $query->have_posts() ) {
            $query->the_post();

            wc_get_template_part( 'content', 'product' );
        }

        $current_page   = absint( $query->get( 'paged', 1 ) );

        return [
            'current_page' => $current_page,
            'max_page'     => $query->max_num_pages,
            'found_posts'  => $query->found_posts,
            'filters'      => View::get_filters_html(),
            'products'     => ob_get_clean(),
            'result_count' => $this->get_result_count_html( $query->found_posts, $this->get_products_per_page(), $current_page ),
            'pagination'   => $this->get_pagintion_html( $query->max_num_pages, $current_page )
        ];
    }

    public function verify_nonce( \WP_REST_Request $request ) {
        return wp_verify_nonce( $request->get_header( 'X-WP-Nonce' ), 'wp_rest' );
    }

    public function create_query( \WP_REST_Request $request ) {
        $page = $request->has_param( 'paged' ) ? absint( $request->get_param( 'paged' ) ) : 1;

        $query = new \WP_Query();

        $query->set( 'post_status', 'publish' );
        $query->set( 'post_type', 'product' );
        $query->set( 'posts_per_page', $this->get_products_per_page() );
        $query->set( 'paged', $page );
        $query->set( 'ep_integrate', true ); // Add ElasticPress support

        // Trick WC that this is the main query to all the actions/filters are applied
        $GLOBALS['wp_the_query'] = $query;

        return $query;
    }

    /**
     * Get HTML for result count
     * 
     * @param int $total_results Total pages
     * @param int $per_page Products per page
     * @param int $current_page The current page
     * 
     * @return string Result count HTML
     */
    private function get_result_count_html( $total_results, $per_page, $current_page ) {
        $args = array(
			'total'    => $total_results,
			'per_page' => $per_page,
			'current'  => $current_page,
		);

        $args = apply_filters( 'filtry_result_count_args', $args );

		return wc_get_template_html( 'loop/result-count.php', $args );
    }

    /**
     * @param int $total_pages
     * @param int $current_page
     * 
     * Get HTML for pagination
     * 
     * @return string Pagination HTML
     */
    private function get_pagintion_html( $total_pages, $current_page ) {
        $tokens = parse_url( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
        $base   = trailingslashit( get_permalink( wc_get_page_id( 'shop' ) ) ) . 'page/999999999/';

        if( isset( $tokens['query'] ) ) {
            $base .= '?' . $tokens['query'];
        }

        $args = array(
			'total'   => $total_pages,
			'current' => $current_page,
			'base'    => esc_url_raw( str_replace( 999999999, '%#%', $base ) ),
			'format'  => '',
		);

        $args = apply_filters( 'filtry_pagination_args', $args );

		return wc_get_template_html( 'loop/pagination.php', $args );
    }

    /**
     * Return woocommerce products per page
     */
    private function get_products_per_page(): int {
        return absint(
             apply_filters( 'loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page() )
        );
    }
}