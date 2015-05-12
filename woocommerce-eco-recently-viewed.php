<?php
/*
Plugin Name: Woocommerce Recently Viewed Products Widget
Plugin URI: http://wordpress.org/plugins/Woocommerce-Recently-Viewed-Products-Widget/
Description: **Plugin Woocommerce Viewed Products Widget** - Recently viewed products is an incredibly powerful feature simply because it’s, for me, sort of very basic artificial intelligence. It allows users to easily go back to products they already viewed in just a matter of seconds. And the fact to use a Widget to display recently viewed products is great because you can place it everywhere in your website.
Demo : http://mauwebsitedep.com/demo/ecomshopvn/balo-hoc-sinh.html
Version: 1.1
Author: Nguyen Ngoc Linh
Author URI: http://mauwebsitedep.com/gioi-thieu
*/
 

 class ecoSoft_woocommerce_recently_viewed_products extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'ecoSoft_woocommerce_recently_viewed_products', // Base ID
			__( 'EcoSoft Woocommerce Viewed Productst', 'ecoSoft' ), // Name
			array( 'description' => __( 'Add Woocommerce Viewed Productst to Widget', 'ecoSoft' ), ) // Args
		);
		
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		 extract($args);
		 
		// Get WooCommerce Global
		global $woocommerce;
	
		// Get recently viewed product cookies data
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
		$viewed_products = array_filter( array_map( 'absint', $viewed_products ) );
	
		// If no data, quit
		if ( empty( $viewed_products ) )
			return __( 'You have not viewed any product yet!', 'ecoSoft' );
	
		// Create the object
		ob_start();

		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
		global $post, $woocommerce, $product;
		$wp_query = new WP_Query( array(
							'posts_per_page' => 5, 
							'no_found_rows'  => 1, 
							'post_status'    => 'publish', 
							'post_type'      => 'product', 
							'post__in'       => $viewed_products,
							
		
		));
echo '
<div class="widget woocommerce widget_products">
<h3 class="eco_viewed_products"><span>Recently viewed products</span></h3>
<ul class="product_list_widget">';
while ($wp_query->have_posts()) : $wp_query->the_post(); global  $post, $product;?>						
          <li>
	<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
		<?php echo $product->get_image(); ?>
		<span class="product-title"><?php echo $product->get_title(); ?></span>
	</a>
	<?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
	<?php echo $product->get_price_html(); ?>
</li>

<?php
endwhile;
echo '</ul></div>';
wp_reset_query();
wp_reset_postdata();
}	
	
 

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */

	public function form( $instance ) {
		
	}
}
add_action('widgets_init', create_function('', 'return register_widget("ecoSoft_woocommerce_recently_viewed_products");'));