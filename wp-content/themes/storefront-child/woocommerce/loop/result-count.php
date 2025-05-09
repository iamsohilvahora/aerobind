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
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<p class="woocommerce-result-count">
	<?php
	$current_page = max(1, get_query_var('paged') );
	$total_pages = $wp_query->max_num_pages;

	if ( $total <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		printf( _n( 'Showing the single result', 'Showing all %d results similar to: <span><strong>'.get_search_query().'</strong></span>', $total, 'woocommerce' ), $total );
	} else {
		/* translators: 1: first result 2: last result 3: total results */
		printf( _nx(
 'Showing the single result', 'Showing' . $current_page . 'of %3$d results similar to: <span><strong>'.get_search_query().'</strong></span>', $total, 'with first and last result', 'woocommerce' ), $first, $last, $total );
	}
	?>
</p>
