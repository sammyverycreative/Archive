<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php
$product_id = get_the_ID();
$product = wc_get_product( $product_id );
$price = $product->get_price_html();
?>

<div class="infos">
	<div class="cell x4">
		<div class="product-gallery">
			<div class="product-image">
				<div class="product-img" style="background-image: url(<?php echo get_the_post_thumbnail_url($product_id); ?>);"></div>
			</div>
			<?php	
				$thumb_id = get_post_thumbnail_id();
				$thumbsArgs = array(
					'post_type' 	=> 'attachment',
					'numberposts' 	=> -1,
					'post_status' 	=> null,
					'post_parent' 	=> $product_id,
					'post__not_in'	=> array($thumb_id),
					'post_mime_type'=> 'image',
					'orderby'		=> 'menu_order',
					'order'			=> 'ASC'
				);
				$attachments = get_posts($thumbsArgs);
				if ($attachments) :
					$loop = 0;
					$columns = apply_filters('woocommerce_product_thumbnails_columns', 3);
					foreach ( $attachments as $attachment ) :
						if (get_post_meta($attachment->ID, '_woocommerce_exclude_image', true)==1) continue;
						$loop++;
						$_post = & get_post( $attachment->ID );
						$url = wp_get_attachment_url($_post->ID);
						$post_title = esc_attr($_post->post_title);
						//$image = '<div class="product-image">'.wp_get_attachment_image($attachment->ID, 'full').'</div>';
						$thumb = wp_get_attachment_image_src($attachment->ID, 'full');
						$image = '<div class="product-image"><div class="product-img" style="background-image: url('.$thumb[0].');"></div></div>';
						echo $image;
					endforeach;
				endif;
			?>
		</div>
	</div>
	<div class="cell x8">
		<div class="details">
			<div class="title">
				<h3><?php the_title(); ?></h3>
				<?php echo $product->get_categories( ', ', '<span class="category">' . _n( '', '', sizeof( get_the_terms( $post->ID, 'product_cat' ) ), 'woocommerce' ) . ' ', '</span>' ); ?>
			</div>
			<div class="price">
				<span>
					<?php echo $price; ?>
				</span>
			</div>
			<div class="content">
				<?php the_content(); ?>
			</div>
			<div class="add-to-cart">
				<?php /*<input type="text" placeholder="1">
				<a href="#" class="simple-btn"><?php _e('Add to cart', THEME_TEXT_DOMAIN) ?></a>*/ ?>
				<form class="cart" action="<?php echo esc_url( get_permalink() ); ?>" method="post" enctype='multipart/form-data'>
					<?php
					do_action( 'woocommerce_before_add_to_cart_quantity' );
					woocommerce_quantity_input( array(
						'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
						'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
						'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
					) );
					do_action( 'woocommerce_after_add_to_cart_quantity' );
					?>
					<button type="submit" class="simple-btn"><?php _e('Add to cart', THEME_TEXT_DOMAIN) ?></button>
					<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
					<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
					<input type="hidden" name="variation_id" class="variation_id" value="0" />
				</form>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="other-details">
	<div id="tabs-menu">
		<div class="tabsjquery tab">
			<ul>
				<li><a href="#" class="tablinks active"><?php _e('Description', THEME_TEXT_DOMAIN) ?></a></li>
				<?php if (get_field('use_title')) { ?><li><a href="#" class="tablinks"><?php the_field('use_title'); ?></a></li><?php } ?>
			</ul>
		</div>
	</div>
	<div id="tabs-content">
		<div class="tabcontent active">
			<div class="tab-content">
				<?php the_excerpt(); ?>
			</div>
		</div>
		<?php if (get_field('use_title')) { ?>
		<div class="tabcontent">
			<div class="tab-content">
				<?php the_field('use_text'); ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<div class="similar-products">
	<?php
	if ($product_tags = get_the_terms($product->id, 'product_tag')) {
	    //Product Tags IDs array
	    $product_tag_ids = array();
	    foreach($product_tags as $product_tag) {
	        $product_tag_ids[] = $product_tag->term_id;
	    }
	}
	$currentID = get_the_ID();
	$args = array(
        'post_type'             => 'product',
        'ignore_sticky_posts'   => true,
        'posts_per_page' 		=> 3,
        'orderby'               => 'date',
        'order'=> 'DSC',
        'post__not_in'          => array($currentID),
        'tax_query'             => array(array(
                                        'taxonomy'  => 'product_tag',
                                        'field'     => 'id',
                                        'terms'     => $product_tag_ids,
        )),
    );
	$related = new WP_Query( $args );
	if ( $related->have_posts() ) : ?>
	    <div class="heading">
			<h3><?php _e('You May Also Like', THEME_TEXT_DOMAIN) ?></h3>
		</div>
		<div class="products-row">
	            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
	            	<?php $theID = get_the_ID(); ?>
	                <div class="cell x4">
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
										<?php
										$relatedProduct = wc_get_product( $theID );
										$relatedPrice = $relatedProduct->get_price_html();
										?>
										<span><?php echo $relatedPrice; ?></span>
									</div>
									<a href="<?php the_permalink(); ?>" class="simple-btn"><?php _e('Add to cart', THEME_TEXT_DOMAIN) ?></a>
								</div>
							</div>
						</div>
					</div>
	            <?php endwhile; ?>
	        <div class="clear"></div>
	    </div>
	<?php endif;
	wp_reset_postdata(); ?>
</div>