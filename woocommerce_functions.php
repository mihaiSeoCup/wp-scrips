<?php 
/* add the following script in your functions.php file and use them where you see fit */

/* get count for every propduct category*/
function get_product_category_count_custom( $term_id ) {

	$product_visibility_terms  = wc_get_product_visibility_term_ids();
	$product_visibility_not_in[] = $product_visibility_terms['outofstock'];

   $args = array(
      'post_type'     => 'product',
      'orderby'       => 'DESC',
      'posts_per_page'=> -1,
      'tax_query' => array(
      		'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $term_id,
			),
		    array(
	      		'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_not_in,
				'operator' => 'NOT IN',
		    )
		),
    );
    $the_query = new WP_Query( $args );
   	return  count($the_query->posts);

	wp_reset_postdata();

}
