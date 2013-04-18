<?php

################################################################################
// Testimonial Pagination
################################################################################
function testimonials_paginate() {
	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => true,
		'type' => 'plain'
	);
	if( $wp_rewrite->using_permalinks() ) $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
	if( !empty($wp_query->query_vars['s']) ) $pagination['add_args'] = array( 's' => get_query_var( 's' ) );
	echo paginate_links( $pagination );
}

$testimonials_prefix = 'dbt_';
$testimonials_meta_box = array(
	'id' => 'testimonials-meta-box',
	'title' => 'Testimonials Options',
	'page' => 'testimonials',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => 'Select an Agent',
			'id' => $testimonials_prefix . 'testimonials_agent_select',
			'type' => 'select_agent'
		)   
	)
);

################################################################################
// Testimonial Add Meta Box
################################################################################
add_action('admin_menu', 'testimonials_add_box');
// Add meta box
function testimonials_add_box() {
	global $testimonials_meta_box;
	add_meta_box($testimonials_meta_box['id'], $testimonials_meta_box['title'], 'testimonials_show_box', $testimonials_meta_box['page'], $testimonials_meta_box['context'], $testimonials_meta_box['priority']);
}
/* gets the property agents dropdown */
function get_testimonials_agent_dropdown() {
	$testimonials_agent_loop = new WP_Query( array( 'post_type' => 'agents',  'post_status' => 'publish',  'posts_per_page' => -1, 'caller_get_posts'=> 1) );
	$current_testimonials_agent = get_post_meta(get_the_ID(), 'dbt_testimonials_agent_select', true);
	if ( $testimonials_agent_loop->have_posts() ) :
	while ( $testimonials_agent_loop->have_posts() ) : $testimonials_agent_loop->the_post();
		$testimonials_agent_title = get_the_title();
		echo '<option';
		if (!empty($current_testimonials_agent)) {
		foreach ($current_testimonials_agent as $current_testimonial_agent) {
			if ($current_testimonial_agent == $testimonials_agent_title) { echo ' selected="selected"';}
		}}
		echo ' value="'.$testimonials_agent_title.'">'; echo $testimonials_agent_title; echo '</option>';
	endwhile; endif;
	wp_reset_query();
}
// Callback function to show fields in meta box
function testimonials_show_box() {
	global $testimonials_meta_box, $post;
	// Use nonce for verification
	echo '<input type="hidden" name="testimonials_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	echo '<table class="form-table">';
	foreach ($testimonials_meta_box['fields'] as $field) {
		// get current post meta data
		$testimonials_meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
			case 'select_agent':
				echo '<tr><td colspan="2"><h4>Assign Agents or Multiple Agents</h4></td></tr><tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<select multiple="multiple" style="height:100px;" name="', $field['id'], '[]" id="', $field['id'], '[]">';
				echo '<option value="none">No Agents</option>';
				$testimonials_agent_holder = $post;
				get_testimonials_agent_dropdown();
				$post = $testimonials_agent_holder;
				echo '</select>';
				echo     '</td>','</tr>';
			break;     
		}
	}
	echo '</table>';
}
add_action('save_post', 'testimonials_save_data');
// Save data from meta box
function testimonials_save_data($post_id) {
	global $testimonials_meta_box;
	// verify nonce
	if (!wp_verify_nonce( isset($_POST['testimonials_meta_box_nonce']), basename(__FILE__))) {
		return $post_id;
	}
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	foreach ($testimonials_meta_box['fields'] as $field) {
		$testimonials_old = get_post_meta($post_id, $field['id'], true);
		$testimonials_new = $_POST[$field['id']];
		if ($testimonials_new && $testimonials_new != $testimonials_old) {
			update_post_meta($post_id, $field['id'], $testimonials_new);
		} elseif ('' == $testimonials_new && $testimonials_old) {
			delete_post_meta($post_id, $field['id'], $testimonials_old);
		}
	}
}

################################################################################
// Testimonial Custom Post Type
################################################################################
add_action('init', 'testimonials_register');
function testimonials_register() {
	$labels = array(
		'name' => _x('Testimonials', 'post type general name'),
		'singular_name' => _x('Testimonial', 'post type singular name'),
		'add_new' => _x('Add New', 'Testimonial item'),
		'add_new_item' => __('Add New Testimonial'),
		'edit_item' => __('Edit Testimonial'),
		'new_item' => __('New Testimonial'),
		'view_item' => __('View Testimonial'),
		'search_items' => __('Search Testimonial'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' =>  WP_PLUGIN_URL.  '/real-estate-by-rewebapps/images/testimonials-icon.png',
		'rewrite' => array('true', 'with_front' => false),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail', 'tags'),
		'has_archive' => true
	  ); 
	register_post_type( 'testimonials' , $args );
}

################################################################################
// Assign Testimonials to Agents
################################################################################
function the_testimonials_agent() {
	$get_testimonials_agent = get_post_meta(get_the_ID(), 'dbt_testimonials_agent_select', true);
	$the_testimonials_agent = $get_testimonials_agent;
	$total = count($the_testimonials_agent);
	$i=0;
	if ($the_testimonials_agent == '' || $the_testimonials_agent == "none") { } else { 
	echo 'Testimonial for ';
		foreach($the_testimonials_agent as $testimonials_agent) {
			$i++;
			echo '<a href="';
			echo home_url();
			echo '/agents/';
			echo link_replace_callback($testimonials_agent);
			echo '">';
			echo $testimonials_agent;
			echo '</a>';
			if ($i != $total && $i != $total-1) echo', ';
			if ($i == $total-1) echo ' and ';
		}
	echo '</li><li class="clear"></li>';

	 }
}

################################################################################
// Testimonials per Page
################################################################################
function testimonials_posts_per_page($query) {
	if ( isset($query->query_vars['post_type']) == 'testimonials' ) $query->query_vars['posts_per_page'] = 10;
	return $query;
}
if ( !is_admin() ) add_filter( 'pre_get_posts', 'testimonials_posts_per_page' );

################################################################################
// Remove quick edit for testimonials
################################################################################
add_filter( 'post_row_actions', 'remove_testimonials_row_actions', 10, 2 );
function remove_testimonials_row_actions( $actions, $post )
{
  global $current_screen;
	if( $current_screen->post_type != 'testimonials' ) return $actions;
	//unset( $actions['edit'] );
	//unset( $actions['view'] );
	//unset( $actions['trash'] );
	unset( $actions['inline hide-if-no-js'] );
	//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );

	return $actions;
}


################################################################################
// Load Template Files
################################################################################

add_filter( 'template_include', 'include_testimonials_template', 1 );

function include_testimonials_template( $template_path ) {
	if ( get_post_type() == 'testimonials' ) {
		// Single Property Template
		if ( is_single() ) {
			// checks if the file exists in the theme first,
			// otherwise serve the file from the plugin
			if ( $theme_file = locate_template( array ( 'single-testimonials.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . '../templates/single-testimonials.php';
			}
		}

		// Archive Template
		if ( is_archive() ) {
			// checks if the file exists in the theme first,
			// otherwise serve the file from the plugin
			if ( $theme_file = locate_template( array ( 'archive-testimonials.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . '../templates/archive-testimonials.php';
			}
		}


	}
	return $template_path;
}

