<?php
/**
 * Plugin Name: Product offset pagination
 * Plugin URI: https://github.com/rtcamp
 * Description: GraphQL offset pagination support for products page.
 * Version: 1.0.0
 * Author: rtCamp
 * Author URI: https://github.com/rtcamp
 * Text Domain: wp-graphql-product-offset-pagination
 * Requires at least: 5.7
 * Requires PHP: 7.0
 * Credit: https://github.com/valu-digital/wp-graphql-offset-pagination/issues/1#issuecomment-810011026
 */

/**
 * Add offset pagination to products.
 */
function wp_graphql_add_offset_pagination_to_products() {
	register_graphql_field(
		'RootQueryToProductConnectionWhereArgs',
		'offsetPagination',
		array(
			'type'        => 'OffsetPagination',
			'description' => 'Paginate content nodes with offsets',
		)
	);
}
add_action( 'graphql_register_types', 'wp_graphql_add_offset_pagination_to_products' );

/**
 * Add offset pagination to products.
 *
 * @param array $query_args query args.
 * @param array $where_args where query args.
 */
function wp_graphql_filter_map_offset_to_wp_query_args(
	array $query_args,
	array $where_args
) {
	if ( isset( $where_args['offsetPagination']['offset'] ) ) {
		$query_args['offset'] = $where_args['offsetPagination']['offset'];
	}
	if ( isset( $where_args['offsetPagination']['size'] ) ) {
		$query_args['posts_per_page'] =
			intval( $where_args['offsetPagination']['size'] ) + 1;
	}
	return $query_args;
}
add_filter( 'graphql_map_input_fields_to_product_query', 'wp_graphql_filter_map_offset_to_wp_query_args', 10, 2 );
