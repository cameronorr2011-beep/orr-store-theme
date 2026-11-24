<?php
/**
 * Header.
 *
 * @package OrrStore
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php esc_html_e( 'Skip to content', 'orr-store' ); ?></a>

<header class="site-head">
	<div class="wrap">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>

		<?php if ( function_exists( 'wc_get_cart_url' ) ) : ?>
			<?php $orr_store_count = orr_store_cart_count(); ?>
			<a class="cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
				<?php esc_html_e( 'Cart', 'orr-store' ); ?>
				<span aria-hidden="true">(<?php echo esc_html( number_format_i18n( $orr_store_count ) ); ?>)</span>
				<span class="screen-reader-text">
					<?php
					printf(
						/* translators: %s: number of items in the cart. */
						esc_html( _n( '%s item in cart', '%s items in cart', $orr_store_count, 'orr-store' ) ),
						esc_html( number_format_i18n( $orr_store_count ) )
					);
					?>
				</span>
			</a>
		<?php endif; ?>
	</div>
</header>
