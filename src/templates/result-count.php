<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p class="woocommerce-result-count">
	<?php
		$is_last_page  = $current * $per_page >= $total;
		$only_one_page = $total <= $per_page || -1 === $per_page; 

		// phpcs:disable WordPress.Security
		if ( $is_last_page || $only_one_page ) {
			/* translators: %d: total results */
			printf( _n( '%d result', '%d results', $total, 'filtry' ), $total );
		} else {
			$current_results = min( $current * $per_page, $total );

			/* translators: 1: current results 2: total results */
			printf( __( '%1$d of %2$d results', 'filtry' ), $current_results, $total );
		}
		// phpcs:enable WordPress.Security
	?>
</p>
