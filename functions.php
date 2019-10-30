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



/*
* Custom post type Agentii
*/
// Register Custom Post Type
function agentie() {

	$labels = array(
		'name'                  => _x( 'Agentii', 'Post Type General Name', 'cazino' ),
		'singular_name'         => _x( 'Agentie', 'Post Type Singular Name', 'cazino' ),
		'menu_name'             => __( 'Agentii', 'cazino' ),
		'name_admin_bar'        => __( 'Agentii', 'cazino' ),
		'archives'              => __( 'Item Archives', 'cazino' ),
		'attributes'            => __( 'Item Attributes', 'cazino' ),
		'parent_item_colon'     => __( 'Parent Item:', 'cazino' ),
		'all_items'             => __( 'All Items', 'cazino' ),
		'add_new_item'          => __( 'Add New Item', 'cazino' ),
		'add_new'               => __( 'Add New', 'cazino' ),
		'new_item'              => __( 'New Item', 'cazino' ),
		'edit_item'             => __( 'Edit Item', 'cazino' ),
		'update_item'           => __( 'Update Item', 'cazino' ),
		'view_item'             => __( 'View Item', 'cazino' ),
		'view_items'            => __( 'View Items', 'cazino' ),
		'search_items'          => __( 'Search Item', 'cazino' ),
		'not_found'             => __( 'Not found', 'cazino' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'cazino' ),
		'featured_image'        => __( 'Featured Image', 'cazino' ),
		'set_featured_image'    => __( 'Set featured image', 'cazino' ),
		'remove_featured_image' => __( 'Remove featured image', 'cazino' ),
		'use_featured_image'    => __( 'Use as featured image', 'cazino' ),
		'insert_into_item'      => __( 'Insert into item', 'cazino' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'cazino' ),
		'items_list'            => __( 'Items list', 'cazino' ),
		'items_list_navigation' => __( 'Items list navigation', 'cazino' ),
		'filter_items_list'     => __( 'Filter items list', 'cazino' ),
	);
	$args = array(
		'label'                 => __( 'Agentie', 'cazino' ),
		'description'           => __( 'Agentiile siteului', 'cazino' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ),
		'taxonomies' 			=> array('post_tag','category', 'game_category'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite'				=> array( 'slug' => 'joc')
	);
	register_post_type( 'agentie', $args );

}
add_action( 'init', 'agentie', 0 );

//create a custom taxonomy name it "type" for your posts
function game_taxonomy_category() {
 
  $labels = array(
    'name' => _x( 'Categorii', 'taxonomy general name' ),
    'singular_name' => _x( 'Categorie', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Types' ),
    'all_items' => __( 'All Game category' ),
    'parent_item' => __( 'Parent Game category' ),
    'parent_item_colon' => __( 'Parent Game category:' ),
    'edit_item' => __( 'Edit Game category' ), 
    'update_item' => __( 'Update Game category' ),
    'add_new_item' => __( 'Add New Game category' ),
    'new_item_name' => __( 'New Game category' ),
    'menu_name' => __( 'Categorii' ),
  ); 	
 
  register_taxonomy('game_category',array('game'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'game_category' ),
  ));
}


/*
* get all post_types
*/
add_action( 'init', 'wpse34410_init', 0, 99 );
function wpse34410_init() 
{
    $types = get_post_types( [], 'objects' );
   // print_r($types);
}
