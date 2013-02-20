<?php


################################################################################
// Setup Pagination
################################################################################
function paginate() {
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


################################################################################
// Setup Meta Boxes
################################################################################

$prop_prefix = 'dbt_';
/* Sets up all of custom data fields in the admin edit page. If you need new fields, here would be the place to add it, in the form of an array. */
$prop_meta_box = array(
    'id' => 'prop-meta-box',
    'title' => 'Property Details',
    'page' => 'properties',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Select a Gallery',
            'id' => $prop_prefix . 'select',
            'type' => 'gal_select'
        ),
    	array(
            'name' => 'List Price',
            'id' => $prop_prefix . 'list_price',
            'type' => 'price',
            'std' => ''
        ),
        array(
            'name' => 'List Date',
            'id' => $prop_prefix . 'list_date',
            'type' => 'date',
            'std' => ''
        ),
        array(
            'name' => 'Sold Price',
            'id' => $prop_prefix . 'sold_price',
            'type' => 'price',
            'std' => ''
        ),
        array(
            'name' => 'Date Sold',
            'id' => $prop_prefix . 'sold_date',
            'type' => 'endgroup1',
            'std' => ''
        ),
        array(
            'name' => 'Street Address',
            'id' => $prop_prefix . 'prop_address',
            'type' => 'text',
            'std' => ''
        ),
         array(
            'name' => 'City',
            'id' => $prop_prefix . 'prop_city',
            'type' => 'text',
            'std' => ''
        ),
         array(
            'name' => 'State',
            'id' => $prop_prefix . 'prop_state',
            'type' => 'text',
            'std' => ''
        ),
         array(
            'name' => 'Zip Code',
            'id' => $prop_prefix . 'prop_zip',
            'type' => 'endgroup2',
            'std' => ''
        ),
        array(
         	'name' => 'MLS ID',
         	'id' => $prop_prefix . 'mls_id',
         	'type' => 'text',
         	'std' => ''
         ),
         array(
            'name' => 'Bedrooms',
            'id' => $prop_prefix . 'prop_bed',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Bathrooms',
            'id' => $prop_prefix . 'prop_bath',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Half Baths',
            'id' => $prop_prefix . 'prop_half_bath',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Garage Spaces',
            'id' => $prop_prefix . 'prop_garage',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Living Space',
            'id' => $prop_prefix . 'prop_living_space',
            'type' => 'footage',
            'std' => ''
        ),
        array(
            'name' => 'Land Size',
            'id' => $prop_prefix . 'prop_land_size',
            'type' => 'footage',
            'std' => ''
        ),
        array(
            'name' => 'Virtual Tour Link',
            'id' => $prop_prefix . 'prop_virtual_tour',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Select a Neighborhood',
            'id' => $prop_prefix . 'neighborhood_select',
            'type' => 'select_neigh',
            'options' => array('Option 1', 'Option 2', 'Option 3')
        ),
        array(
            'name' => 'Select a Agent',
            'id' => $prop_prefix . 'prop_agent_select',
            'type' => 'select_agent',
            'options' => array('Option 1', 'Option 2', 'Option 3')
        )
    )
);

################################################################################
// Create Meta Boxes
################################################################################
add_action('admin_menu', 'prop_add_box');
// Add meta box
function prop_add_box() {
    global $prop_meta_box;
    add_meta_box($prop_meta_box['id'], $prop_meta_box['title'], 'prop_show_box', $prop_meta_box['page'], $prop_meta_box['context'], $prop_meta_box['priority']);
}

################################################################################
// NextGenGallery Dropdown
################################################################################
function get_ngg_prop_gallerydropdown($prop_currid = '') {
	get_ngg_prop_dropdown($prop_currid);
}
function get_ngg_prop_dropdown($prop_currid = '') {
	global $wpdb;
	if (!$wpdb->nggallery) return;

	$prop_tables = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY 'name' ASC ");
	if($prop_tables) {
		foreach($prop_tables as $prop_table) {
			echo '<option value="'.$prop_table->gid.'" ';
			if ($prop_table->gid == $prop_currid) echo "selected='selected' ";
				echo '>'.$prop_table->title.'</option>'."\n\t"; 
		}

	}
}

################################################################################
// Get Neighborhoods Dropdown
################################################################################
function get_prop_neigh_dropdown() {
	$neigh_loop = new WP_Query( array( 'post_type' => 'neighborhoods', 'showposts' => '-1', 'orderby' => 'title', 'order' => 'ASC') );
	$current_neighborhood = get_post_meta(get_the_ID(), 'dbt_neighborhood_select', true);
	if ( $neigh_loop->have_posts() ) :
	while ( $neigh_loop->have_posts() ) : $neigh_loop->the_post();
		$neigh_title = get_the_title();
		echo '<option';
		if($current_neighborhood == $neigh_title) { echo ' selected="selected"';}
		echo ' value="'.$neigh_title.'">'; echo $neigh_title; echo '</option>';
	endwhile; endif;
	wp_reset_query();
}

################################################################################
// Get Agents Dropdown
################################################################################
function get_prop_agent_dropdown() {
	$prop_agent_loop = new WP_Query( array( 'post_type' => 'agents', 'showposts' => '-1', 'orderby' => 'title', 'order' => 'ASC') );
	$current_prop_agent = get_post_meta(get_the_ID(), 'dbt_prop_agent_select', true);
	if ( $prop_agent_loop->have_posts() ) :
	
	while ( $prop_agent_loop->have_posts() ) : $prop_agent_loop->the_post();
		$prop_agent_title = get_the_title();
		echo '<option';
		if (!empty($current_prop_agent)) {
		foreach ($current_prop_agent as $current_prop_a) {
		if ($current_prop_a == $prop_agent_title) { echo ' selected="selected"';}
		}}
		echo ' value="'.$prop_agent_title.'">'; echo $prop_agent_title; echo '</option>';
	endwhile; endif;
	wp_reset_query();
}
// Callback function to show fields in meta box
function prop_show_box() {
    global $prop_meta_box, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="prop_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table" style="overflow:hidden;">';

	foreach ($prop_meta_box['fields'] as $field) {
		// get current post meta data
		$prop_meta = get_post_meta($post->ID, $field['id'], true);
		
		
		switch ($field['type']) {
			case 'gal_select':
				echo '<tr><td colspan="2"><h4>Property Gallery (Requires NextGen-Gallery Plugin)</h4></td></tr>';
				echo '<tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				echo '<option value="none">No Gallery</option>';
				get_ngg_prop_gallerydropdown($prop_meta);
				echo '</select>';
				
				echo '&emsp; <a href="http://plugin.dev/wp-admin/admin.php?page=nggallery-manage-gallery&mode=edit&gid=' .$prop_meta . '">Edit Gallery</a>';
				echo     '</td>','</tr>';
				echo '<tr><td colspan="2"><strong>Note:</strong>&emsp; You must first choose a gallery, then save or update the property before using the "Edit Gallery" link.</td></tr>';
				echo '<tr><td colspan="2"><hr style="background:#ddd; border:0px; height:1px; position:relative; width:100%;" /><h4>Property Price Information</h4></td></tr>';
			break;
			case 'price':
				echo '<tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td><div style="margin-left:-10px;">';
				echo '$ <input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $prop_meta ? $prop_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);
				echo     '</div></td>','</tr>';
			break;
			case 'text':
				echo '<tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $prop_meta ? $prop_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);
				echo     '</td>','</tr>';
			break;
			case 'footage':
				echo '<tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $prop_meta ? $prop_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', ' SqFt.<br />', isset($field['desc']);
				echo     '</td>','</tr>';
			break;
			case 'date':
			echo '<tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $prop_meta ? $prop_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" class="datepicker" />', '<br />', isset($field['desc']);
				echo     '</td>','</tr>';
			break;
			case 'endgroup1':
				echo '<tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $prop_meta ? $prop_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" class="datepicker" />', '<br />', isset($field['desc']);
				echo     '</td>','</tr>';
				echo '<tr><td colspan="2"><hr style="background:#ddd; border:0px; height:1px; position:relative; width:100%;" /><h4>Property Location</h4></td></tr>';
			break;
			case 'endgroup2':
				echo '<tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $prop_meta ? $prop_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);
				echo     '</td>','</tr>';
				echo '<tr><td colspan="2"><hr style="background:#ddd; border:0px; height:1px; position:relative; width:100%;" /><h4>About the Property</h4></td></tr>';
			break;
			
			case 'select_neigh':
				echo '<tr><td colspan="2"><hr style="background:#ddd; border:0px; height:1px; position:relative; width:100%;" /><h4>Attach a Neighborhood to this Property</h4></td></tr><tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				echo '<option value="none">No Neighborhood</option>';
				$neigh_holder = $post;
				get_prop_neigh_dropdown();
				$post = $neigh_holder;
				echo '</select>';
				echo     '</td>','</tr>';
			break;
			case 'select_agent':
				echo '<tr><td colspan="2"><hr style="background:#ddd; border:0px; height:1px; position:relative; width:100%;" /><h4>Assign Agents or Multiple Agents</h4></td></tr><tr>',
				'<th style="width:15%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				echo '<select multiple="multiple" style="height:100px;" name="', $field['id'], '[]" id="', $field['id'], '[]">';
				echo '<option value="">No Agents</option>';
				$agent_holder = $post;
				get_prop_agent_dropdown();
				$post = $agent_holder;
				echo '</select>';
				echo     '</td>','</tr>';
			break;
			
		}
	
	}
	echo '</table>';
	}
	
################################################################################
// Save Meta Data
################################################################################
add_action('save_post', 'prop_save_data');
// Save data from meta box
function prop_save_data($post_id) {
    global $prop_meta_box;
    // verify nonce
    if (!wp_verify_nonce( isset($_POST['prop_meta_box_nonce']), basename(__FILE__))) {
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
    foreach ($prop_meta_box['fields'] as $field) {
        $prop_old = get_post_meta($post_id, $field['id'], true);
        $prop_new = $_POST[$field['id']];
        if ($prop_new && $prop_new != $prop_old) {
            update_post_meta($post_id, $field['id'], $prop_new);
        } elseif ('' == $prop_new && $prop_old) {
            delete_post_meta($post_id, $field['id'], $prop_old);
        }
    }
}

################################################################################
// Create Properties Custom Post Type
################################################################################
add_action('init', 'property_listing_register');
function property_listing_register() {
	$labels = array(
		'name' => _x('Properties', 'post type general name'),
		'singular_name' => _x('Property', 'post type singular name'),
		'add_new' => _x('Add New', 'Properties item'),
		'add_new_item' => __('Add New Property'),
		'edit_item' => __('Edit Properties'),
		'new_item' => __('New Property'),
		'view_item' => __('View Property'),
		'search_items' => __('Search Properties'),
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
		'menu_icon' =>  WP_PLUGIN_URL.  '/real-estate-by-imforza/images/property-icon.png',
		'rewrite' => array('true', 'with_front' => false),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail', 'author'),
		'has_archive' => true
	  ); 
	  
################################################################################
// Register Taxonomies - Status, Type
################################################################################
 	register_taxonomy("property-status", array("properties"), array("hierarchical" => true, "label" => "Property Status", "rewrite" => array('slug' => '/properties/status', 'with_front' => false)));
 	register_taxonomy("property-type", array("properties"), array("hierarchical" => true, "label" => "Property Type", "rewrite" => array('slug' => '/properties/type', 'with_front' => false)));
	register_post_type( 'properties' , $args );
}

################################################################################
// Load Javascript and CSS
################################################################################
if (is_admin()) {
add_action('admin_init', 'prop_plugin_load');
add_action('init', 'prop_plugin_load');
}
function prop_plugin_load()
{
	// Load Javascript
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	
	// Load Datepicker CSS
	wp_enqueue_style('jquery-ui-custom', WP_PLUGIN_URL.'/real-estate-by-imforza/css/ui.datepicker.css' );
	
}


################################################################################
// Enable Datepicker
################################################################################
function my_admin_footer() {
	?>
	<script type="text/javascript">
jQuery(function(){jQuery(".datepicker").datepicker()});
	</script>
	<?php
}
add_action('admin_footer', 'my_admin_footer');


################################################################################
// Custom Columns for Properties Post Type
################################################################################
add_filter( 'manage_properties_posts_columns', 'rewa_prop_cpt_columns' );
add_action('manage_properties_posts_custom_column', 'rewa_prop_cpt_custom_column', 10, 2);

function rewa_prop_cpt_columns($defaults) {
	unset($defaults['author']);
	$defaults['title'] = 'Property Name';
    $defaults['property-status'] = 'Status';
    $defaults['property-type'] = 'Type';
    $defaults['date'] = 'Listed';
    return $defaults;
}
function rewa_prop_cpt_custom_column($column_name, $post_id) {
    $taxonomy = $column_name;
    $post_type = get_post_type($post_id);
    $terms = get_the_terms($post_id, $taxonomy);
 
    if ( !empty($terms) ) {
        foreach ( $terms as $term )
            $post_terms[] = "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " . esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
        echo join( ', ', $post_terms );
    }
    else echo '<i>No terms.</i>';
}


################################################################################
// Property Functions
################################################################################
function the_prop_gallery() {
	$prop_gallery_id = get_post_meta(get_the_ID(), 'dbt_select', true);
	if ($prop_gallery_id == 'none') { } else { 
	
		echo do_shortcode('<div class="prop-gallery">[nggallery id='.$prop_gallery_id.' template=galleryview images=0]</div>');
	 }
}
function is_decimal( $val ) {
    return is_numeric( $val ) && floor( $val ) != $val;
}
function number_format_unlimited_precision($number,$decimal = '.')	{
	if (is_decimal($number)) {
		$broken_number = explode($decimal,$number);
		if($broken_number[2]==0){
			return number_format((double)$broken_number[0]);
		} else {
			return number_format((double)$broken_number[0]).$decimal.$broken_number[2];
		}
	} else {
		return $number;
	}
}
/* List Price */
function the_list_price() {
	$get_list_price = get_post_meta(get_the_ID(), 'dbt_list_price', true);
	$unformatted_list_price = str_replace(array(',', ''), array('', ''), $get_list_price);
	$the_list_price = number_format_unlimited_precision($unformatted_list_price);
	if ($the_list_price == '0' || $the_list_price == '') { } else { 
		if (is_singular()) {
		echo '<li class="pdb-item-title">List Price</li><li class="pdb-item"> $'.$the_list_price.'</li>';
		} else {
			echo '<li class="list-price"><strong>List Price</strong>: $'.$the_list_price.'</li>';
		}
	
	 }
}
/* List Date */
function the_list_date() {
	$get_list_date = get_post_meta(get_the_ID(), 'dbt_list_date', true);
	$the_list_date = $get_list_date;
	if ($the_list_date == '') { } else { 
		echo '<li class="pdb-item-title">List Date</li><li class="pdb-item">'.$the_list_date.'</li>';
	 }
}
/* Price Sold */
function the_sold_price() {
	$get_sold_price = get_post_meta(get_the_ID(), 'dbt_sold_price', true);
	$unformatted_sold_price = str_replace(array(',', ''), array('', ''), $get_sold_price);
	$the_sold_price = number_format_unlimited_precision($unformatted_sold_price);
	if ($the_sold_price == '0' || $the_sold_price == '') { } else { 
		echo '<li class="pdb-item-title">Sold Price</li><li class="pdb-item">$ '.$the_sold_price.'</li>';
	 }
}
/* Date Sold */
function the_sold_date() {
	$get_sold_date = get_post_meta(get_the_ID(), 'dbt_sold_date', true);
	$the_sold_date = $get_sold_date;
	if ($the_sold_date == '') { } else { 
		echo '<li class="pdb-item-title">Sold Date</li><li class="pdb-item">'.$the_sold_date.'</li>';
	 }
}
function link_replace_callback($matches)
	{
	  $linkUrl = str_replace(" ","-",strtolower($matches));
	  return $linkUrl;
	}
/* Property Neighborhood */
function the_prop_neighborhood() {
	$get_prop_neighborhood = get_post_meta(get_the_ID(), 'dbt_neighborhood_select', true);
	$the_prop_neighborhood = $get_prop_neighborhood;
	if ($the_prop_neighborhood == '' || $the_prop_neighborhood == "none") { } else { 
	
		echo '<li class="pdb-item-title">Neighborhood:<li><li class="pdb-item"><a href="';
		echo home_url();
		echo '/neighborhoods/';
		echo link_replace_callback($the_prop_neighborhood);
		echo '">';
		echo $the_prop_neighborhood;
		echo '</a></li><li class="clear"></li>';
	 }
}
/* Property Agent */
function the_prop_agent() {
	$get_prop_agent = get_post_meta(get_the_ID(), 'dbt_prop_agent_select', true);
	$the_prop_agent = $get_prop_agent;
	
	$total = count($the_prop_agent);
	$i=0;
	if ($the_prop_agent == '' || $the_prop_agent == "none") { } else { 
	echo '<li class="pdb-item-title">Agent(s):<li><li class="pdb-item">';
	
		foreach($the_prop_agent as $prop_agent) {
			$i++;
			echo '<a href="';
			echo home_url();
			echo '/agents/';
			echo link_replace_callback($prop_agent);
			echo '">';
			echo $prop_agent;
			echo '</a>';
			if ($i != $total) echo', ';
		}
		
	
	echo '</li><li class="clear"></li>';


		
	 }
}
/* Property Address */
function the_prop_address() {
	$get_prop_address = get_post_meta(get_the_ID(), 'dbt_prop_address', true);
	$the_prop_address = $get_prop_address;
	if ($the_prop_address == '') { } else { 
		echo $the_prop_address;
	 }
}
function the_prop_city() {
	$get_prop_city = get_post_meta(get_the_ID(), 'dbt_prop_city', true);
	$the_prop_city = $get_prop_city;
	if ($the_prop_city == '') { } else { 
		echo $the_prop_city;
	 }
}
function the_prop_state() {
	$get_prop_state = get_post_meta(get_the_ID(), 'dbt_prop_state', true);
	$the_prop_state = $get_prop_state;
	if ($the_prop_state == '') { } else { 
		echo $the_prop_state;
	 }
}
function the_prop_zip() {
	$get_prop_zip = get_post_meta(get_the_ID(), 'dbt_prop_zip', true);
	$the_prop_zip = $get_prop_zip;
	if ($the_prop_zip == '') { } else { 
		echo $the_prop_zip;
	 }
}
function the_mls_id() {
	$get_mls_id = get_post_meta(get_the_ID(), 'dbt_mls_id', true);
	$the_mls_id = $get_mls_id;
	if ($the_mls_id == '') { } else { 
		echo '<li class="pdb-item-title">MLS ID</li><li class="pdb-item">'.$the_mls_id.'</li><li class="clear"></li>';
	 }
}
function the_prop_bed() {
	$get_prop_bed = get_post_meta(get_the_ID(), 'dbt_prop_bed', true);
	$the_prop_bed = $get_prop_bed;
	if ($the_prop_bed == '') { } else { 
		if (is_singular()) {
			echo '<li class="pdb-item-title">Bedrooms</li><li class="pdb-item">'.$the_prop_bed.'</li><li class="clear"></li>';
		} else {
			echo '<li><strong>Bedrooms</strong>: '.$the_prop_bed.'</li>';
		}
	 }
}
function the_prop_bath() {
	$get_prop_bath = get_post_meta(get_the_ID(), 'dbt_prop_bath', true);
	$the_prop_bath = $get_prop_bath;
	if ($the_prop_bath == '') { } else { 
		if (is_singular()) {
			echo '<li class="pdb-item-title">Bathrooms</li><li class="pdb-item">'.$the_prop_bath.'</li><li class="clear"></li>';
	 	} else {
	 		echo '<li><strong>Bathrooms</strong>: '.$the_prop_bath.'</li>';
	 	}
	 }
}
function the_prop_half_bath() {
	$get_prop_half_bath = get_post_meta(get_the_ID(), 'dbt_prop_half_bath', true);
	$the_prop_half_bath = $get_prop_half_bath;
	if ($the_prop_half_bath == '') { } else { 
		echo '<li class="pdb-item-title">Half Baths</li><li class="pdb-item">'.$the_prop_half_bath.'</li><li class="clear"></li>';
	 }
}
function the_prop_garage() {
	$get_prop_garage = get_post_meta(get_the_ID(), 'dbt_prop_garage', true);
	$the_prop_garage = $get_prop_garage;
	if ($the_prop_garage == '') { } else { 
		echo '<li class="pdb-item-title">Garage Spaces</li><li class="pdb-item">'.$the_prop_garage.'</li><li class="clear"></li>';
	 }
}
function the_prop_living_space() {
	$get_prop_living_space = get_post_meta(get_the_ID(), 'dbt_prop_living_space', true);
	$the_prop_living_space = $get_prop_living_space;
	if ($the_prop_living_space == '') { } else { 
		echo '<li class="pdb-item-title">Living Space</li><li class="pdb-item">'.$the_prop_living_space.' SqFt.</li><li class="clear"></li>';
	 }
}
function the_prop_land_size() {
	$get_prop_land_size = get_post_meta(get_the_ID(), 'dbt_prop_land_size', true);
	$the_prop_land_size = $get_prop_land_size;
	if ($the_prop_land_size == '') { } else { 
		echo '<li class="pdb-item-title">Lot Size</li><li class="pdb-item">'.$the_prop_land_size.' SqFt.</li><li class="clear"></li>';
	 }
}
function the_prop_virtual_tour() {
	$get_prop_virtual_tour = get_post_meta(get_the_ID(), 'dbt_prop_virtual_tour', true);
	$the_prop_virtual_tour = $get_prop_virtual_tour;
	if ($the_prop_virtual_tour == '') { } else { 
		echo '<div class="virtual-tour"><a href="'.$the_prop_virtual_tour.'" target="_blank">Virtual Tour</a></div>';
	 }
}
function properties_posts_per_page($query) {
    if (isset( $query->query_vars['post_type']) == 'properties' && is_post_type_archive() ) $query->query_vars['posts_per_page'] = 5;
    return $query;
}
if ( !is_admin() ) add_filter( 'pre_get_posts', 'properties_posts_per_page' );


################################################################################
// Remove quick edit for properties
################################################################################
add_filter( 'post_row_actions', 'remove_prop_row_actions', 10, 2 );
function remove_prop_row_actions( $actions, $post )
{
  global $current_screen;
	if( $current_screen->post_type != 'properties' ) return $actions;
	//unset( $actions['edit'] );
	//unset( $actions['view'] );
	//unset( $actions['trash'] );
	unset( $actions['inline hide-if-no-js'] );
	//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );

	return $actions;
}

################################################################################
// Change Text for Publish Box
################################################################################
function wps_translation_mangler($translation, $text, $domain) {
        global $post;
    if (isset($post->post_type) == 'properties') {
        $translations = &get_translations_for_domain( $domain);
        if ( $text == 'Scheduled for: <b>%1$s</b>') {
            return $translations->translate( 'Listed Date: <b>%1$s</b>' );
        }
        if ( $text == 'Published on: <b>%1$s</b>') {
            return $translations->translate( 'Listed Date: <b>%1$s</b>' );
        }
        if ( $text == 'Publish <b>immediately</b>') {
            return $translations->translate( 'Listed Date: <b>%1$s</b>' );
        }
    }
    return $translation;
}
add_filter('gettext', 'wps_translation_mangler', 10, 4);






################################################################################
// Load Template Files
################################################################################

add_filter( 'template_include', 'include_properties_template', 1 );

function include_properties_template( $template_path ) {
    if ( get_post_type() == 'properties' ) {
    	// Single Property Template
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-properties.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '../templates/single-properties.php';
            }
        }
        
        // Archive Template
        if ( is_archive() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'archive-properties.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '../templates/archive-properties.php';
            }
        }
    
        
    }
    return $template_path;
}
