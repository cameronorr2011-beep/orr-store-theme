<?php
/**
 * The one page: hero, then every published WooCommerce product.
 *
 * @package OrrStore
 */

get_header();

$orr_store_products = orr_store_products();

$orr_store_heading = apply_filters(
	'orr_store_heading',
	__( 'Live cultures, shipped cold.', 'orr-store' )
);

$orr_store_intro = apply_filters(
	'orr_store_intro',
	__( 'Marine microalgae strains, marine salt and a one-gallon starter kit — packed Monday to Wednesday and sent from the lab.', 'orr-store' )
);
?>
<main id="main">

	<section class="hero">
		<div class="wrap">
			<h1><?php echo esc_html( $orr_store_heading ); ?></h1>
			<p><?php echo esc_html( $orr_store_intro ); ?></p>
		</div>
	</section>

	<section class="products" aria-label="<?php esc_attr_e( 'Products', 'orr-store' ); ?>">
		<div class="wrap">
			<?php if ( empty( $orr_store_products ) ) : ?>

				<p class="notice">
					<?php
					if ( ! function_exists( 'wc_get_products' ) ) {
						esc_html_e( 'WooCommerce is not active yet. Install and activate WooCommerce, then add your first product.', 'orr-store' );
					} else {
						esc_html_e( 'No products yet. Add your first product under WooCommerce → Products.', 'orr-store' );
					}
					?>
				</p>

			<?php else : ?>

				<ul class="grid">
					<?php foreach ( $orr_store_products as $orr_store_product ) : ?>

						<li class="card">
							<a class="card__media" href="<?php echo esc_url( $orr_store_product->get_permalink() ); ?>">
								<?php echo $orr_store_product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce returns escaped markup. ?>
							</a>

							<h2>
								<a href="<?php echo esc_url( $orr_store_product->get_permalink() ); ?>">
									<?php echo esc_html( $orr_store_product->get_name() ); ?>
								</a>
							</h2>

							<p class="price"><?php echo wp_kses_post( $orr_store_product->get_price_html() ); ?></p>

							<a class="btn" href="<?php echo esc_url( $orr_store_product->add_to_cart_url() ); ?>">
								<?php echo esc_html( $orr_store_product->add_to_cart_text() ); ?>
							</a>
						</li>

					<?php endforeach; ?>
				</ul>

			<?php endif; ?>
		</div>
	</section>

</main>
<?php
get_footer();
