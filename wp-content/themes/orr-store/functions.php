<?php
/**
 * Orr Store — theme setup.
 *
 * @package OrrStore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_template_directory() . '/inc/catalog.php';

/**
 * Register theme supports, including WooCommerce.
 */
function orr_store_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 1000,
			'product_grid'          => array(
				'default_rows'    => 4,
				'min_rows'        => 1,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 4,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'orr_store_setup' );

/**
 * Load the stylesheet.
 */
function orr_store_assets() {
	wp_enqueue_style(
		'orr-store',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'orr_store_assets' );

/**
 * Tell WooCommerce this theme is compatible with custom order tables.
 */
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

/**
 * Number of items in the cart, for the header link.
 *
 * @return int
 */
function orr_store_cart_count() {
	if ( function_exists( 'WC' ) && WC()->cart instanceof WC_Cart ) {
		return (int) WC()->cart->get_cart_contents_count();
	}

	return 0;
}

/**
 * Products shown on the single page.
 *
 * @return WC_Product[]
 */
function orr_store_products() {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	return wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => 24,
			'orderby' => 'menu_order',
			'order'   => 'ASC',
		)
	);
}
