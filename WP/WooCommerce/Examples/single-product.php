<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<?php
$shopID = get_option('woocommerce_shop_page_id');
?>

<?php if( have_rows('product', $shopID) ): 
	while( have_rows('product', $shopID) ): the_row(); 
		// vars
		$image = get_sub_field('single_background');
		?>
	<?php endwhile; ?>
<?php endif; ?>

<div class="single-product">
	<div class="hero" style="background-image: url(<?php $vc->img->the_img($image,'landscape'); ?>);"></div>
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
	</div>
	</div>
</div>

<?php get_footer( 'shop' );