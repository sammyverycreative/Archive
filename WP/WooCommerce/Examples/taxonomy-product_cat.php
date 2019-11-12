<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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

get_header('shop'); ?>

<?php
$shopID = get_option('woocommerce_shop_page_id');
$term_object = get_queried_object();
?>

<?php if( have_rows('shop', $shopID) ): 
	while( have_rows('shop', $shopID) ): the_row(); 
		// vars
		$image = get_sub_field('background');
		$title = get_sub_field('title');
		$subtitle = get_sub_field('subtitle');
		$certificates = get_sub_field('certificates');
		?>
	<?php endwhile; ?>
<?php endif; ?>

<div class="products-page">
	<div class="hero" style="background-image: url(<?php $vc->img->the_img($image,'landscape'); ?>);">
		<div class="overlay"></div>
		<div class="container">
			<div class="hero-wrap">
				<div class="title">
					<?php if ($title) { ?><h3><?php echo $title; ?></h3><?php } ?>
					<?php if ($subtitle) { ?><h1><?php echo $subtitle; ?></h1><?php } ?>
				</div>
				<?php if ($certificates) { ?>
					<div class="certificates">
						<ul>
							<?php foreach ($certificates as $certificate) { ?>
								<li><?php $vc->img->print_img($certificate['certificate'],false,get_bloginfo('name')); ?></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="container">
			<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'product_cat' => $term_object->slug
					);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					$count = 0; $len = $loop->post_count; /*$array = array(1, 2, 4, 5, 7, 8);*/
					while ( $loop->have_posts() ) : $loop->the_post();
						$count++;
						wc_get_template_part( 'content', 'product' );
						/*if ($count % 1 && !in_array($count, $array)) { echo '<div class="clear"></div>'; }*/
					endwhile;
					?>
					<div class="clear"></div>

					<?php if ($len > 9) { ?><div class="load-more">
						<a href="#" class="simple-btn" id="loadMore"><?php _e('Load More', THEME_TEXT_DOMAIN) ?></a>
					</div><?php } ?>
					
					<?php
				} else {
					_e( '<div class="center"><h4>No products were found.</h4></div>', 'woocommerce' );
				}
				wp_reset_postdata();
			?>
		</div>
	</div>
</div>

<?php get_footer('shop');