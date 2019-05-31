<?php
/**
 * Claue child theme functions.
 *
 * @since   1.0.0
 * @package Claue
 */

/**
 * Enqueue style of child theme
 */
function jas_claue_enqueue_script() {
	wp_enqueue_style( 'jas-claue-parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'jas_claue_enqueue_script' );

/**
 * Restore CSV upload functionality for WordPress 4.9.9 and up
 */
add_filter('wp_check_filetype_and_ext', function($values, $file, $filename, $mimes) {
	if ( extension_loaded( 'fileinfo' ) ) {
		// with the php-extension, a CSV file is issues type text/plain so we fix that back to
		// text/csv by trusting the file extension.
		$finfo     = finfo_open( FILEINFO_MIME_TYPE );
		$real_mime = finfo_file( $finfo, $file );
		finfo_close( $finfo );
		if ( $real_mime === 'text/plain' && preg_match( '/\.(csv)$/i', $filename ) ) {
			$values['ext']  = 'csv';
			$values['type'] = 'text/csv';
		}
	} else {
		// without the php-extension, we probably don't have the issue at all, but just to be sure...
		if ( preg_match( '/\.(csv)$/i', $filename ) ) {
			$values['ext']  = 'csv';
			$values['type'] = 'text/csv';
		}
	}
	return $values;
}, PHP_INT_MAX, 4);

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5aa2fbbea0fc1',
	'title' => 'Skroutz',
	'fields' => array(
		array(
			'key' => 'field_5aa2fbcf2ca2c',
			'label' => 'Προσθήκη σε skroutz.gr',
			'name' => 'skroutz_add',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => 'Ναι',
			'ui_off_text' => 'Όχι',
		),
		array(
			'key' => 'field_5aa2fc5f1e2c2',
			'label' => 'Κατηγορία Skroutz',
			'name' => 'skroutz_category',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5aa2fbcf2ca2c',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'taxonomy' => 'skroutz_category',
			'field_type' => 'select',
			'allow_null' => 0,
			'add_term' => 1,
			'save_terms' => 1,
			'load_terms' => 1,
			'return_format' => 'object',
			'multiple' => 0,
		),
		array(
			'key' => 'field_5aa2fe7b25fc3',
			'label' => 'Διαθεσιμότητα',
			'name' => 'skroutz_availability',
			'type' => 'select',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5aa2fbcf2ca2c',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Άμεση παραλαβή / Παράδoση 1 έως 3 ημέρες' => 'Άμεση παραλαβή / Παράδoση 1 έως 3 ημέρες',
				'Παράδοση σε 1 - 3 ημέρες' => 'Παράδοση σε 1 - 3 ημέρες',
				'Παράδοση σε 4 - 10 ημέρες' => 'Παράδοση σε 4 - 10 ημέρες',
				'Κατόπιν Παραγγελίας' => 'Κατόπιν Παραγγελίας',
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'return_format' => 'label',
			'placeholder' => '',
		),
		array(
			'key' => 'field_5aa32438e1a81',
			'label' => 'Έξοδα Αποστολής',
			'name' => 'skroutz_shipping_cost',
			'type' => 'number',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5aa2fbcf2ca2c',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '€',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_5aa306aa4cf58',
			'label' => 'Κατασκευαστής',
			'name' => 'skroutz_manufacturer',
			'type' => 'taxonomy',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5aa2fbcf2ca2c',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'taxonomy' => 'skroutz_manufacturer',
			'field_type' => 'select',
			'allow_null' => 0,
			'add_term' => 1,
			'save_terms' => 1,
			'load_terms' => 1,
			'return_format' => 'object',
			'multiple' => 0,
		),
		array(
			'key' => 'field_5aa308d41ae96',
			'label' => 'Κωδικός',
			'name' => 'skroutz_mpn',
			'type' => 'text',
			'instructions' => 'Κωδικός προϊόντος από κατασκευαστή (προαιρετικό)',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5aa2fbcf2ca2c',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5aa3083ea8e20',
			'label' => 'Χρώμα',
			'name' => 'skroutz_color',
			'type' => 'text',
			'instructions' => 'Προαιρετικό',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5aa2fbcf2ca2c',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5aa3097e07255',
			'label' => 'Μέγεθος',
			'name' => 'skroutz_size',
			'type' => 'text',
			'instructions' => 'Προαιρετικό',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5aa2fbcf2ca2c',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'product',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;

/**
 * Register custom taxonomies.
 */
add_action( 'init', function() {
	register_taxonomy( 'skroutz_category', array( 'product' ), array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => 'Κατηγορίες Skroutz',
			'singular_name'     => 'Κατηγορία Skroutz',
			'search_items'      => 'Αναζήτηση',
			'all_items'         => 'Όλες οι κατηγορίες',
			'parent_item'       => 'Γονική Κατηγορία',
			'parent_item_colon' => 'Γονική Κατηγορία:',
			'edit_item'         => 'Επεξεργασία Κατηγορίας',
			'update_item'       => 'Ενημέρωση Κατηγορίας',
			'add_new_item'      => 'Προσθήκη Κατηγορίας',
			'new_item_name'     => 'Όνομα Νέας Κατηγορίας',
			'menu_name'         => 'Κατηγορία Skroutz',
		),
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
	) );
	register_taxonomy( 'skroutz_manufacturer', array( 'product' ), array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'              => 'Κατασκευαστής (Skroutz)',
			'singular_name'     => 'Κατασκευαστής (Skroutz)',
			'search_items'      => 'Αναζήτηση',
			'all_items'         => 'Όλοι οι κατασκευαστές',
			'edit_item'         => 'Επεξεργασία Κατασκευαστή',
			'update_item'       => 'Ενημέρωση Κατασκευαστή',
			'add_new_item'      => 'Προσθήκη Κατασκευαστή',
			'new_item_name'     => 'Όνομα Νέου Κατασκευαστή',
			'menu_name'         => 'Κατασκευαστής (Skroutz)',
		),
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
	) );
}, 5 );

function eboy_products_cats_boys() {
	$args = array(
			'post_type' => 'product',
		//	'product_cat' => 'μπλούζες',
			's' => 'αγόρια',
			'posts_per_page' => -1

			);
		$loop = new WP_Query( $args );

		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
		//		wp_update_term(31, 'product_cat', array(
		//		  'name' => 'Κορίτσι',
		//		  'slug' => 'κορίτσι'
		//		));
		// An array of IDs of categories we to add to this post.
		$cat_ids = array( 16 );


		$address_post_id = get_the_ID() ;
		/*
		 * If this was coming from the database or another source, we would need to make sure
		 * these were integers:

		$cat_ids = array_map( 'intval', $cat_ids );
		$cat_ids = array_unique( $cat_ids );

		 */

		// Add these categories, note the last argument is true.
		$term_taxonomy_ids = wp_set_object_terms( $address_post_id, $cat_ids, 'product_cat', true );

		if ( is_wp_error( $term_taxonomy_ids ) ) {
			// There was an error somewhere and the terms couldn't be set.
		} else {
			// Success! These categories were added to the post.
		}
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
}
add_action ('eboy_products', 'eboy_products_cats_boys', 10 );


function eboy_products_cats_girls() {
	$args = array(
			'post_type' => 'product',
		//	'product_cat' => 'μπλούζες',
			's' => 'κορίτσια',
			'posts_per_page' => -1

			);
		$loop = new WP_Query( $args );

		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
		//		wp_update_term(31, 'product_cat', array(
		//		  'name' => 'Κορίτσι',
		//		  'slug' => 'κορίτσι'
		//		));
		// An array of IDs of categories we to add to this post.
		$cat_ids = array( 31 );


		$address_post_id = get_the_ID() ;
		/*
		 * If this was coming from the database or another source, we would need to make sure
		 * these were integers:

		$cat_ids = array_map( 'intval', $cat_ids );
		$cat_ids = array_unique( $cat_ids );

		 */

		// Add these categories, note the last argument is true.
		$term_taxonomy_ids = wp_set_object_terms( $address_post_id, $cat_ids, 'product_cat', true );

		if ( is_wp_error( $term_taxonomy_ids ) ) {
			// There was an error somewhere and the terms couldn't be set.
		} else {
			// Success! These categories were added to the post.
		}
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
}
add_action ('eboy_products', 'eboy_products_cats_girls', 15 );
