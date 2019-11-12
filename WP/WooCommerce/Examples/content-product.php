<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>

<?php
$product_id = get_the_ID();
$product = wc_get_product( $product_id );
$regularPrice = $product->get_regular_price();
$salePrice = $product->get_sale_price();
$thePrice = $product->get_price();
$price = $product->get_price_html()
?>

<div class="cell x4 loadMore-div">
	<div class="product-wrap">
		<div class="product">
			<?php if (get_field('badge') != 'None' && !is_front_page()) { ?>
			<span class="badge <?php if (get_field('badge') == 'New') { echo 'red'; }
									 elseif (get_field('badge') == 'Discount') { echo 'yellow'; }
									 else { echo ''; }
								?>" <?php if (get_field('badge') != 'New' || get_field('badge') == 'Discount') { echo 'style="background-color:'.get_field('color').';"'; } ?>>
				<span>
					<?php if (get_field('badge') != 'None') { the_field('badge'); } ?>
				</span>
			</span>
			<?php } ?>
			<div class="image" style="background-image: url(<?php the_post_thumbnail_url(); ?>)"><a href="<?php the_permalink(); ?>"></a></div>
			<div class="info">
				<div class="title">
					<span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
				</div>
				<div class="price">
					<span><?php echo $price; ?></span>
				</div>
				<a href="<?php the_permalink(); ?>" class="simple-btn"><?php _e('Add to cart', THEME_TEXT_DOMAIN) ?></a>
			</div>
		</div>
	</div>
</div>