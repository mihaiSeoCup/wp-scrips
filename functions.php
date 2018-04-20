<?php
//add <meta name="robots" content="noindex,follow"> to paged pages eg: /page/2
add_filter( 'wpseo_robots', 'my_robots_func' );
function my_robots_func( $robotsstr ) {
	if (  is_paged() ) {

		$robotsstr = 'noindex,follow';		
	}
	return $robotsstr;
}


//add alt atribute to woocoommerce images
add_filter('wp_get_attachment_image_attributes', 'change_attachement_image_attributes', 20, 2);

function change_attachement_image_attributes( $attr, $attachment ){
    // Get post parent
    $parent = get_post_field( 'post_parent', $attachment);

    // Get post type to check if it's product
    // $type = get_post_field( 'post_type', $parent);
    // if( $type != 'product' ){
    //     return $attr;
    // }

    /// Get title
    $title = get_post_field( 'post_title', $parent)." A4office";

    $attr['alt'] = $title;
    $attr['title'] = $title;

    return $attr;
}

//get woocoommerce prices from widget
function get_price_range_funtion_custom() {
 	$method = new ReflectionMethod("WC_Widget_Price_Filter" , "get_filtered_price");
	$method->setAccessible(true);

	return $method->invoke(new WC_Widget_Price_Filter);
}
