<?php
/**
 * Regular pages: WooCommerce cart, checkout, account, product pages.
 *
 * @package OrrStore
 */

get_header();
?>
<main id="main" class="page">
	<div class="wrap">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<h1><?php the_title(); ?></h1>
			<div class="entry">
				<?php the_content(); ?>
			</div>
			<?php
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
