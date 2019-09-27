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

//yoast breadcrumbs change schema.org/schema
//special tanks to https://gist.github.com/beaverbuilder
$breadcrumb_count = 1;
add_filter( 'wpseo_breadcrumb_single_link', 'ss_breadcrumb_single_link', 10, 2 );
function ss_breadcrumb_single_link( $link_output, $link ) {
    
    global $breadcrumb_count; 
    $element = 'li';
    $element = esc_attr( apply_filters( 'wpseo_breadcrumb_single_link_wrapper', $element ) );
    $link_output = '<' . $element . ' itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    
    if ( isset( $link['url'] )) {
       
        $link_output .= '<a itemprop="item" href="' . esc_url( $link['url'] ) . '"><span itemprop="name">' . esc_html( $link['text'] ) . '</span></a><meta itemprop="position" content="'.$breadcrumb_count++.'" />'; 
    } 
    
    $link_output .= '</' . $element . '>';
    return $link_output;
}
add_filter( 'wpseo_breadcrumb_output_wrapper', 'ss_breadcrumb_output_wrapper', 10, 1 );
function ss_breadcrumb_output_wrapper( $wrapper) {
     
    $wrapper = 'ol';
    return $wrapper;
}

// define the wpseo_breadcrumb_output callback 
function filter_wpseo_breadcrumb_output( $output ) { 
    // make filter magic happen here... 
    return '<div id="breadcrumbs">' .$output . '</div>';
}; 
         
// add the filter 
add_filter( 'wpseo_breadcrumb_output', 'filter_wpseo_breadcrumb_output', 10, 1 ); 



/*
* Redirection page redirect
*/
add_action('template_redirect', 'redirect_agency', 10);
function redirect_agency() {
	// i think wp_redirect() will work too, instead of header()
	header("Location: ".$_GET['link'].'/');
	exit;	

}
