<?php
/**
 * The shop catalog.
 *
 * Creates the ten products the shop sells — six strains at $15, salt at $5,
 * liquid f/2 at $10, the salt + f/2 bundle at $13 and the one-gallon kit at
 * $35 — with their photos, prices and categories.
 *
 * It runs once: on theme activation, or on the next admin page load if
 * WooCommerce was activated after the theme. It only runs when the store has
 * no products yet, so an existing catalog is never touched or duplicated.
 *
 * @package OrrStore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ORR_STORE_CATALOG_VERSION' ) ) {
	define( 'ORR_STORE_CATALOG_VERSION', '1' );
}

/**
 * Every product in the shop, in display order.
 *
 * @return array
 */
function orr_store_catalog() {
	return array(
		array(
			'sku'         => 'ORR-NANNO',
			'name'        => 'Nannochloropsis oculata — 16 oz live culture',
			'price'       => '15',
			'category'    => 'Live cultures',
			'image'       => 'nannochloropsis-oculata.jpg',
			'description' => 'Small round green cells that grow dense in a gallon with f/2. Feeds rotifers, brine shrimp and early larvae — the one to start with. Ships with 50 mL of f/2.',
		),
		array(
			'sku'         => 'ORR-TISO',
			'name'        => 'Isochrysis aff. galbana (T-Iso) — 16 oz live culture',
			'price'       => '15',
			'category'    => 'Live cultures',
			'image'       => 'tisochrysis-lutea.jpg',
			'description' => 'The base feed in most oyster hatcheries: DHA-rich, flagellated and fast to divide, so it stays in the water column with gentle air. Ships with 50 mL of f/2.',
		),
		array(
			'sku'         => 'ORR-PAVLOVA',
			'name'        => 'Pavlova lutheri — 16 oz live culture',
			'price'       => '15',
			'category'    => 'Live cultures',
			'image'       => 'pavlova-lutheri.jpg',
			'description' => 'DHA and EPA in one cell, which is why scallop and mussel programmes use it. Slower and less forgiving than T-Iso — usually part of the ration. Ships with 50 mL of f/2.',
		),
		array(
			'sku'         => 'ORR-CHAETO',
			'name'        => 'Chaetoceros muelleri — 16 oz live culture',
			'price'       => '15',
			'category'    => 'Live cultures',
			'image'       => 'chaetoceros-muelleri.jpg',
			'description' => 'A silica-walled chain diatom for late larvae and post-set. Order the f/2 with silicate or the culture stalls after a couple of divisions. Ships with 50 mL of f/2.',
		),
		array(
			'sku'         => 'ORR-THAL',
			'name'        => 'Thalassiosira pseudonana (3H) — 16 oz live culture',
			'price'       => '15',
			'category'    => 'Live cultures',
			'image'       => 'thalassiosira-pseudonana.jpg',
			'description' => 'A small centric diatom that fits early larvae where a chain would be too big. Silicate-dependent, and quick to be outcompeted between batches. Ships with 50 mL of f/2.',
		),
		array(
			'sku'         => 'ORR-TETRA',
			'name'        => 'Tetraselmis suecica — 16 oz live culture',
			'price'       => '15',
			'category'    => 'Live cultures',
			'image'       => 'tetraselmis-suecica.jpg',
			'description' => 'Large, green and robust — a late-larval and broodstock feed, and a standard rotifer enrichment. Too big for a first-feeding veliger. Ships with 50 mL of f/2.',
		),
		array(
			'sku'         => 'ORR-SALT',
			'name'        => 'Marine salt — 1,000 g',
			'price'       => '5',
			'category'    => 'Supplies',
			'image'       => '',
			'description' => '1,000 g of marine salt, enough to mix 28–30 litres at 30 ppt. The same salt the cultures are grown in. Mixes clear in a few minutes of stirring.',
		),
		array(
			'sku'         => 'ORR-F2',
			'name'        => 'f/2 nutrient media — 50 mL liquid',
			'price'       => '10',
			'category'    => 'Supplies',
			'image'       => '',
			'description' => '50 mL of liquid f/2 concentrate: nitrate, phosphate, trace metals and vitamins. Dose 2 mL per litre of culture — one bottle treats about 25 litres.',
		),
		array(
			'sku'         => 'ORR-SALT-F2',
			'name'        => 'Salt + f/2 bundle',
			'price'       => '13',
			'category'    => 'Supplies',
			'image'       => '',
			'description' => '1,000 g of marine salt and 50 mL of liquid f/2 together — everything for a fresh gallon of seawater and the food to go in it. $13 instead of $15 bought separately.',
		),
		array(
			'sku'         => 'ORR-KIT',
			'name'        => 'One-gallon culture kit',
			'price'       => '35',
			'category'    => 'Kits',
			'image'       => 'culturekit-hero.jpg',
			'description' => 'One box, one gallon: a gallon carboy, 16 oz of live culture, 50 mL of f/2, 1,000 g of marine salt, an air pump, airline, an LED light and a Secchi stick. Choose the strain in the box notes at checkout.',
		),
	);
}

/**
 * Copy a photo from the theme into the media library, once.
 *
 * @param string $filename File name inside the theme's assets directory.
 * @return int Attachment ID, or 0.
 */
function orr_store_attachment( $filename ) {
	if ( ! $filename ) {
		return 0;
	}

	$imported = get_option( 'orr_store_attachments', array() );
	if ( ! empty( $imported[ $filename ] ) && get_post( $imported[ $filename ] ) ) {
		return (int) $imported[ $filename ];
	}

	$source = get_template_directory() . '/assets/' . $filename;
	if ( ! file_exists( $source ) ) {
		return 0;
	}

	$bits = wp_upload_bits( $filename, null, file_get_contents( $source ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions -- Local theme asset.
	if ( ! empty( $bits['error'] ) ) {
		return 0;
	}

	$type       = wp_check_filetype( $bits['file'] );
	$attachment = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'],
			'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
			'post_status'    => 'inherit',
		),
		$bits['file']
	);

	if ( is_wp_error( $attachment ) || ! $attachment ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $bits['file'] ) );

	$imported[ $filename ] = (int) $attachment;
	update_option( 'orr_store_attachments', $imported, false );

	return (int) $attachment;
}

/**
 * Create the catalog if the store is still empty.
 *
 * @return void
 */
function orr_store_seed_catalog() {
	if ( get_option( 'orr_store_catalog_version' ) === ORR_STORE_CATALOG_VERSION ) {
		return;
	}

	if ( ! class_exists( 'WC_Product_Simple' ) ) {
		return; // WooCommerce is not active yet; try again later.
	}

	$existing = wp_count_posts( 'product' );
	if ( ! empty( $existing->publish ) || ! empty( $existing->draft ) ) {
		update_option( 'orr_store_catalog_version', ORR_STORE_CATALOG_VERSION, false );
		return; // Never touch a store that already has products.
	}

	$terms = array();

	foreach ( orr_store_catalog() as $index => $item ) {
		if ( wc_get_product_id_by_sku( $item['sku'] ) ) {
			continue;
		}

		if ( ! isset( $terms[ $item['category'] ] ) ) {
			$term                = wp_insert_term( $item['category'], 'product_cat' );
			$terms[ $item['category'] ] = is_wp_error( $term ) ? 0 : (int) $term['term_id'];
		}

		$product = new WC_Product_Simple();
		$product->set_name( $item['name'] );
		$product->set_sku( $item['sku'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_description( $item['description'] );
		$product->set_short_description( $item['description'] );
		$product->set_regular_price( $item['price'] );
		$product->set_price( $item['price'] );
		$product->set_manage_stock( false );
		$product->set_stock_status( 'instock' );
		$product->set_menu_order( $index + 1 );

		if ( $terms[ $item['category'] ] ) {
			$product->set_category_ids( array( $terms[ $item['category'] ] ) );
		}

		$image_id = orr_store_attachment( $item['image'] );
		if ( $image_id ) {
			$product->set_image_id( $image_id );
		}

		$product->save();
	}

	update_option( 'orr_store_catalog_version', ORR_STORE_CATALOG_VERSION, false );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'orr_store_seed_catalog' );
add_action( 'admin_init', 'orr_store_seed_catalog' );
