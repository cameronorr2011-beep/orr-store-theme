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
 * The cart, drawn as a panel on the one page.
 *
 * It is opened by the header link, and by the #cart fragment every
 * add-to-cart link appends, so adding a bottle reopens the cart with the
 * bottle in it.
 *
 * @return void
 */
function orr_store_cart_drawer() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$cart = WC()->cart;
	?>
	<div class="cart-drawer" id="cart">
		<a class="cart-drawer__backdrop" href="#" aria-label="<?php esc_attr_e( 'Close cart', 'orr-store' ); ?>"></a>

		<aside class="cart-drawer__panel" role="dialog" aria-label="<?php esc_attr_e( 'Cart', 'orr-store' ); ?>">
			<div class="cart-drawer__head">
				<h2><?php esc_html_e( 'Cart', 'orr-store' ); ?></h2>
				<a class="cart-drawer__close" href="#" aria-label="<?php esc_attr_e( 'Close cart', 'orr-store' ); ?>">&times;</a>
			</div>

			<?php if ( $cart->is_empty() ) : ?>

				<p class="cart-drawer__empty"><?php esc_html_e( 'Your cart is empty. Add a bottle or the kit.', 'orr-store' ); ?></p>

			<?php else : ?>

				<ul class="cart-drawer__lines">
					<?php
					foreach ( $cart->get_cart() as $orr_store_key => $orr_store_item ) :
						$orr_store_item_product = isset( $orr_store_item['data'] ) ? $orr_store_item['data'] : null;
						if ( ! $orr_store_item_product ) {
							continue;
						}
						?>
						<li class="cart-drawer__line">
							<?php echo $orr_store_item_product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce returns escaped markup. ?>

							<div>
								<p class="cart-drawer__name"><?php echo esc_html( $orr_store_item_product->get_name() ); ?></p>
								<p class="cart-drawer__meta">
									<?php echo esc_html( $orr_store_item['quantity'] ); ?> &times;
									<?php echo wp_kses_post( $cart->get_product_price( $orr_store_item_product ) ); ?>
								</p>
								<a class="cart-drawer__remove" href="<?php echo esc_url( wc_get_cart_remove_url( $orr_store_key ) ); ?>">
									<?php esc_html_e( 'Remove', 'orr-store' ); ?>
								</a>
							</div>

							<span class="cart-drawer__total">
								<?php echo wp_kses_post( $cart->get_product_subtotal( $orr_store_item_product, $orr_store_item['quantity'] ) ); ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="cart-drawer__foot">
					<p class="cart-drawer__subtotal">
						<span><?php esc_html_e( 'Subtotal', 'orr-store' ); ?></span>
						<b><?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?></b>
					</p>
					<p class="cart-drawer__note"><?php esc_html_e( 'Shipping is quoted at checkout. US only.', 'orr-store' ); ?></p>
					<a class="btn" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Checkout', 'orr-store' ); ?></a>
				</div>

			<?php endif; ?>
		</aside>
	</div>
	<?php
}

/**
 * There is one page. Product, shop and cart URLs go back to it.
 *
 * Checkout stays a page of its own because payment needs one.
 *
 * @return void
 */
function orr_store_redirect_to_front() {
	if ( is_admin() || is_front_page() || ! function_exists( 'is_checkout' ) ) {
		return;
	}

	if ( is_checkout() || ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
		return;
	}

	if ( is_product() || is_shop() || is_cart() ) {
		wp_safe_redirect( is_cart() ? home_url( '/#cart' ) : home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'orr_store_redirect_to_front' );

/**
 * After adding a bottle, come back to the one page with the cart open.
 *
 * The browser does not send the #cart fragment in the referer, so the
 * redirect has to put it back.
 *
 * @return string
 */
function orr_store_add_to_cart_redirect() {
	return home_url( '/#cart' );
}
add_filter( 'woocommerce_add_to_cart_redirect', 'orr_store_add_to_cart_redirect' );

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
