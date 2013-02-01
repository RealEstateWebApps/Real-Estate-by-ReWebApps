<?php

/*
Original Reference Source: http://soulsizzle.com/jquery/create-an-ajax-sorter-for-wordpress-custom-post-types/
*/

################################################################################
// Create Agents Post Type
################################################################################
add_action('init', 'agents_listing_register');
function agents_listing_register() {
	$labels = array(
		'name' => _x('Agents', 'post type general name'),
		'singular_name' => _x('Agent', 'post type singular name'),
		'add_new' => _x('Add New', 'Agents item'),
		'add_new_item' => __('Add New Agent'),
		'edit_item' => __('Edit Agents'),
		'new_item' => __('New Agent'),
		'view_item' => __('View Agent'),
		'search_items' => __('Search Agents'),
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
		'menu_icon' =>  WP_PLUGIN_URL.  '/RealEstate/images/agents-icon.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail'),
		'has_archive' => true
	  ); 
 	register_post_type( 'agents' , $args );
}

################################################################################
// Setup Meta Boxes
################################################################################
$agent_prefix = 'dbta_';
$agent_meta_box = array(
    'id' => 'agent-meta-box',
    'title' => 'Agent Details',
    'page' => 'agents',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Job Position',
            'id' => $agent_prefix . 'agent_position',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Email',
            'id' => $agent_prefix . 'agent_email',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Office Number',
            'id' => $agent_prefix . 'agent_office_number',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Mobile',
            'id' => $agent_prefix . 'agent_mobile_number',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Fax Number',
            'id' => $agent_prefix . 'agent_fax_number',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Website',
            'id' => $agent_prefix . 'agent_website',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Facebook',
            'id' => $agent_prefix . 'agent_facebook',
            'type' => 'social_one',
            'std' => ''
        ),
        array(
            'name' => 'Twitter',
            'id' => $agent_prefix . 'agent_twitter',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'LinkedIn',
            'id' => $agent_prefix . 'agent_linkedin',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'YouTube',
            'id' => $agent_prefix . 'agent_youtube',
            'type' => 'text',
            'std' => ''
        )
    )
);


add_action('admin_menu', 'agent_add_box');

################################################################################
// Add Meta Box
################################################################################
function agent_add_box() {
    global $agent_meta_box;
    add_meta_box($agent_meta_box['id'], $agent_meta_box['title'], 'agent_show_box', $agent_meta_box['page'], $agent_meta_box['context'], $agent_meta_box['priority']);
}

// Callback function to show fields in meta box
function agent_show_box() {
    global $agent_meta_box, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="agent_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table" style="overflow:hidden;">';

	foreach ($agent_meta_box['fields'] as $field) {
		// get current post meta data
		$agent_meta = get_post_meta($post->ID, $field['id'], true);
		
		
		switch ($field['type']) {

			case 'text':
				echo '<tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $agent_meta ? $agent_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);
				echo     '</td>','</tr>';
			break;
			case 'social_one':
				echo '<tr><td colspan="2"><hr style="background:#ddd; border:0px; height:1px; position:relative; width:100%;" /><h4>Social Media Profiles</h4></td></tr>', '<tr>', '<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>', '<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $agent_meta ? $agent_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);
				echo     '</td>','</tr>';
			break;
			
		}
	
	}
	echo '</table>';
	}
	
################################################################################
// Save MetaBox Data
################################################################################
add_action('save_post', 'agent_save_data');
// Save data from meta box
function agent_save_data($post_id) {
    global $agent_meta_box;
    // verify nonce
    if (!wp_verify_nonce( isset($_POST['agent_meta_box_nonce']), basename(__FILE__))) {
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
    foreach ($agent_meta_box['fields'] as $field) {
        $agent_old = get_post_meta($post_id, $field['id'], true);
        $agent_new = $_POST[$field['id']];
        if ($agent_new && $agent_new != $agent_old) {
            update_post_meta($post_id, $field['id'], $agent_new);
        } elseif ('' == $agent_new && $agent_old) {
            delete_post_meta($post_id, $field['id'], $agent_old);
        }
    }
}

################################################################################
// Enable Sort Menu
################################################################################
function agents_listing_enable_sort() {
    add_submenu_page('edit.php?post_type=agents', 'Sort Agents', 'Sort', 'edit_posts', basename(__FILE__), 'agents_listing_sort');
}
add_action('admin_menu' , 'agents_listing_enable_sort'); 
 
################################################################################
// Enable Sort Admin
################################################################################
function agents_listing_sort() {
	$agents = new WP_Query('post_type=agents&posts_per_page=-1&orderby=menu_order&order=ASC');
?>
	<div class="wrap">
	<h3>Sort Agents <img src="<?php bloginfo('url'); ?>/wp-admin/images/loading.gif" id="loading-animation" /></h3>
	<ul id="agents-list">
	<?php while ( $agents->have_posts() ) : $agents->the_post(); ?>
		<li id="<?php the_id(); ?>"><?php the_title(); ?></li>			
	<?php endwhile; ?>
	</div><!-- End div#wrap //-->
 
<?php
}
 
################################################################################
// Load Javascript Files
################################################################################
function agents_listing_print_scripts() {
	global $pagenow;
 
	$pages = array('edit.php');
	if (in_array($pagenow, $pages)) {
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('agents_sorting', WP_PLUGIN_URL. '/RealEstate/js/agentsorting.min.js');
	}
}
add_action( 'admin_print_scripts', 'agents_listing_print_scripts' );
 
################################################################################
// Loading All CSS Stylesheets
################################################################################
function agents_listing_print_styles() {
	global $pagenow;
 
	$pages = array('edit.php');
	if (in_array($pagenow, $pages))
		wp_enqueue_style('agent_listing_sorting', WP_PLUGIN_URL. '/RealEstate/css/agentsorting.css');
}
add_action( 'admin_print_styles', 'agents_listing_print_styles' );

################################################################################
// Save Agent List Order
################################################################################
function agents_listing_save_order() {
	global $wpdb; // WordPress database class
 
	$order = explode(',', $_POST['order']);
	$counter = 0;
 
	foreach ($order as $agents_id) {
		$wpdb->update($wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $agents_id) );
		$counter++;
	}
	die(1);
}
add_action('wp_ajax_agents_listing_sort', 'agents_listing_save_order');

################################################################################
// Template Functions
################################################################################
function the_agent_position() {
	$get_agent_position = get_post_meta(get_the_ID(), 'dbta_agent_position', true);
	$the_agent_position = $get_agent_position;
	if ($the_agent_position == '') { } else {
		if (is_singular()) { 
			echo '<li class="agent-position"><h3>',$the_agent_position,'</h3></li>';
		} else {
			echo $the_agent_position;
		}
	 }
}
function the_agent_email() {
	$get_agent_email = get_post_meta(get_the_ID(), 'dbta_agent_email', true);
	$the_agent_email = $get_agent_email;
	if ($the_agent_email == '') { } else { 
		echo '<li class="agent-email"><a href="mailto:',$the_agent_email,'">Email Me</a></li>';
	 }
}
function the_agent_office_number() {
	$get_agent_office_number = get_post_meta(get_the_ID(), 'dbta_agent_office_number', true);
	$the_agent_office_number = $get_agent_office_number;
	if ($the_agent_office_number == '') { } else { 
		echo '<li class="agent-office-number"><strong>O: </strong>',$the_agent_office_number,'</li>';
	 }
}
function the_agent_mobile_number() {
	$get_agent_mobile_number = get_post_meta(get_the_ID(), 'dbta_agent_mobile_number', true);
	$the_agent_mobile_number = $get_agent_mobile_number;
	if ($the_agent_mobile_number == '') { } else { 
		echo '<li class="agent-mobile-number"><strong>M: </strong>',$the_agent_mobile_number,'</li>';
	 }
}
function the_agent_fax_number() {
	$get_agent_fax_number = get_post_meta(get_the_ID(), 'dbta_agent_fax_number', true);
	$the_agent_fax_number = $get_agent_fax_number;
	if ($the_agent_fax_number == '') { } else { 
		echo '<li class="agent-fax"><strong>F: </strong>',$the_agent_fax_number,'</li>';
	 }
}
function the_agent_website() {
	$get_agent_website = get_post_meta(get_the_ID(), 'dbta_agent_website', true);
	$the_agent_website = $get_agent_website;
	if ($the_agent_website == '') { } else { 
		echo '<li class="agent-website"><a href="',$the_agent_website,'" target="_blank">View Website</a></li>';
	 }
}
function the_agent_facebook() {
	$get_agent_facebook = get_post_meta(get_the_ID(), 'dbta_agent_facebook', true);
	$the_agent_facebook = $get_agent_facebook;
	if ($the_agent_facebook == '') { } else { 
		echo '<li class="agent-facebook"><a href="',$the_agent_facebook,'" target="_blank">Like Me</a></li>';
	 }
}
function the_agent_twitter() {
	$get_agent_twitter = get_post_meta(get_the_ID(), 'dbta_agent_twitter', true);
	$the_agent_twitter = $get_agent_twitter;
	if ($the_agent_twitter == '') { } else { 
		echo '<li class="agent-twitter"><a href="',$the_agent_twitter,'" target="_blank">Follow Me</a></li>';
	 }
}
function the_agent_linkedin() {
	$get_agent_linkedin = get_post_meta(get_the_ID(), 'dbta_agent_linkedin', true);
	$the_agent_linkedin = $get_agent_linkedin;
	if ($the_agent_linkedin == '') { } else { 
		echo '<li class="agent-linkedin"><a href="',$the_agent_linkedin,'" target="_blank">Recommend Me</a></li>';
	 }
}
function the_agent_youtube() {
	$get_agent_youtube = get_post_meta(get_the_ID(), 'dbta_agent_youtube', true);
	$the_agent_youtube = $get_agent_youtube;
	if ($the_agent_youtube == '') { } else { 
		echo '<li class="agent-youtube"><a href="',$the_agent_youtube,'" target="_blank">Watch my Videos</a></li>';
	 }
}
################################################################################
// Setup Agent Properties
################################################################################
function the_agent_properties() {
	$args = array(
		'post_type' => 'properties',
		'showposts' => 10,
		'meta_query' => array(
			array(
				'key' => 'dbt_prop_agent_select',
				'value' => get_the_title(),
				'compare' => 'LIKE'
			)
		)
	);
	$agent_prop_query = new WP_Query( $args );
	if ($agent_prop_query->have_posts()) : 
	echo '<li class="view-properties"><a href="'.home_url().'/properties/?agent='.get_the_title().'">View Properties</a></li>';
    endif;
}

################################################################################
// Setup Agent Testimonials
################################################################################
function the_agent_testimonials() {
	$args = array(
		'post_type' => 'testimonials',
		'showposts' => 10,
		'meta_query' => array(
			array(
				'key' => 'dbt_testimonials_agent_select',
				'value' => get_the_title(),
				'compare' => 'LIKE'
			)
		)
	);
	$agent_testimonial_query = new WP_Query( $args );
	if ($agent_testimonial_query->have_posts()) : 
	echo '<li class="view-testimonials"><a href="'.home_url().'/testimonials/?agent='.get_the_title().'">View Testimonials</a></li>';
    endif;
}

################################################################################
// Setup Pagination
################################################################################
function agent_paginate() {
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
function agents_posts_per_page($query) {
    if ( $query->query_vars['post_type'] == 'agents' && is_post_type_archive() ) $query->query_vars['posts_per_page'] = 16;
    return $query;
}

################################################################################
// Remove quick edit for Agents
################################################################################
add_filter( 'post_row_actions', 'remove_agent_row_actions', 10, 2 );
function remove_agent_row_actions( $actions, $post )
{
  global $current_screen;
	if( $current_screen->post_type != 'agents' ) return $actions;
	//unset( $actions['edit'] );
	//unset( $actions['view'] );
	//unset( $actions['trash'] );
	unset( $actions['inline hide-if-no-js'] );
	//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );

	return $actions;
}


################################################################################
// Custom Columns for Agents Post Type
################################################################################
add_action("manage_posts_custom_column",  "agents_custom_columns");
add_filter("manage_edit-agents_columns", "agents_edit_columns");
 
function agents_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Agent Name",
    "position" => "Position",
    "email" => "Email Address",
    "date" => "Date",
  );
 
  return $columns;
}
function agents_custom_columns($column){
  global $post;
 
  switch ($column) {
  case "position":
        $custom = get_post_custom();
        echo $custom["dbta_agent_position"][0];
        break;
    case "email":
      $custom = get_post_custom();
      echo $custom["dbta_agent_email"][0];
      break;
  }
}


