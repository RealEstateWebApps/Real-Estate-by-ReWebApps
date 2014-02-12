<?php

################################################################################
// Setup Pagination for Neighborhoods
################################################################################
function neighborhood_paginate() {
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
// NextGenGallery Dropdown
################################################################################
function get_ngg_gallerydropdown($currid = '') {
	// generates option list for edit dialogs
	get_ngg_dropdown($currid);
}
function get_ngg_dropdown($currid = '') {
	global $wpdb;
	if (!$wpdb->nggallery) return;

	$tables = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY 'name' ASC ");
	if($tables) {
		foreach($tables as $table) {
			echo '<option value="'.$table->gid.'" ';
			if ($table->gid == $currid) echo "selected='selected' ";
				echo '>'.$table->name.'</option>'."\n\t";
		}
	}
}

################################################################################
// Setup Meta Boxes
################################################################################
$prefix = 'dbt_';

$meta_box = array(
    'id' => 'my-meta-box',
    'title' => 'Neighborhood Options',
    'page' => 'neighborhoods',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Select a Gallery',
            'id' => $prefix . 'select',
            'type' => 'select',
            'options' => array('Option 1', 'Option 2', 'Option 3')
        ),
        array(
            'name' => 'City',
            'id' => $prefix . 'neigh_city',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'State',
            'id' => $prefix . 'neigh_state',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Zip',
            'id' => $prefix . 'neigh_zip',
            'type' => 'text',
            'std' => ''
        )
    )
);

add_action('admin_menu', 'mytheme_add_box');

################################################################################
// Add Meta Box
################################################################################
function mytheme_add_box() {
    global $meta_box;

    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}

// Callback function to show fields in meta box
function mytheme_show_box() {
    global $meta_box, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);

			break;
            case 'select':
             	   If (is_plugin_active('nextgen-gallery/nggallery.php')) {
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';


                echo '<option value="none">No Gallery</option>';
                    get_ngg_gallerydropdown($meta);
                echo '</select>';
                echo '&emsp; <a href="'. home_url() .'/wp-admin/admin.php?page=nggallery-manage-gallery&mode=edit&gid=' . $meta . '">Edit Gallery</a>';
                echo '<tr><td colspan="2"><strong>Note:</strong>&emsp; You must first choose a gallery, then save or update the Neighborhood before using the "Edit Gallery" link.</td></tr>';
                } else {
	                echo ' Please install and activate the NextGen-Gallery plugin to use this feature.';
                }
                break;

        }




        echo     '<td>',
            '</tr>';
    }

    echo '</table>';
}

################################################################################
// Save Data from Meta Boxes
################################################################################
add_action('save_post', 'mytheme_save_data');

function mytheme_save_data($post_id) {
    global $meta_box;

    // verify nonce
    if (!wp_verify_nonce( $_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
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

    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

################################################################################
// Register Neighborhoods Custom Post Type
################################################################################
add_action('init', 'neighborhood_register');

function neighborhood_register() {

	$labels = array(
		'name' => _x('Neighborhoods', 'post type general name'),
		'singular_name' => _x('Neighborhood', 'post type singular name'),
		'add_new' => _x('Add New', 'Neighborhood item'),
		'add_new_item' => __('Add New Neighborhood'),
		'edit_item' => __('Edit Neighborhood'),
		'new_item' => __('New Neighborhood'),
		'view_item' => __('View Neighborhood'),
		'search_items' => __('Search Neighborhood'),
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
		'menu_icon' =>  WP_PLUGIN_URL.  '/real-estate-by-rewebapps/images/community-icon.png',
		'rewrite' => array('true', 'with_front' => false),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail', 'tags'),
		'has_archive' => true
	  );

################################################################################
// Neighborhood Category - Taxonomy
################################################################################
 	register_taxonomy("neighborhood-category", array("neighborhoods"), array("hierarchical" => true, 'show_admin_column' => true, "label" => "Neighborhood Category", "rewrite" => array('slug' => 'neighborhood-category', 'with_front' => false), "query_var" => true));
	register_post_type( 'neighborhoods' , $args );

}

### CUSTOM FIELDS FOR res-category
//add extra fields to category edit form hook
################################################################################
// Load Javascript
################################################################################
function neighborhood_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('rewa-neighborhoods', WP_PLUGIN_URL.'/real-estate-by-rewebapps/js/neighborhoods.min.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('rewa-neighborhoods');
}

################################################################################
// Load CSS Stylesheets
################################################################################
function neighborhood_admin_styles() {
wp_enqueue_style('thickbox');
}

################################################################################
// Neighborhood Category - Taxonomy
################################################################################
if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'neighborhood-category') {
add_action('admin_print_scripts', 'neighborhood_admin_scripts');
add_action('admin_print_styles', 'neighborhood_admin_styles');
}

add_action ( 'neighborhood-category_edit_form_fields', 'neighborhood_taxonomy_fields');
//add extra fields to category edit form callback function
function neighborhood_taxonomy_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $tax_meta = get_option( "taxonomy_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Neighborhood Category Image'); ?></label></th>
<td>
<?php echo $tax_meta['img'] ? '<img src="'.$tax_meta['img'].'" style="float:left; margin-right:10px;" class="upload_image" />' : ''; ?><input type="text" name="tax_meta[img]" id="tax_meta[img]" class="upload_image" size="3" style="width:60%;" value="<?php echo $tax_meta['img'] ? $tax_meta['img'] : ''; ?>"><br /><input id="upload_image_button" type="button" value="Upload Image" style="width:20%;" />
        </td>
</tr>
<?php
}
add_action ( 'edited_neighborhood-category', 'save_neighborhood_taxonomy_fileds');
   // save extra category extra fields callback function
function save_neighborhood_taxonomy_fileds( $term_id ) {
    if ( isset( $_POST['tax_meta'] ) ) {
        $t_id = $term_id;
        $tax_meta = get_option( "taxonomy_$t_id");
        $cat_keys = array_keys($_POST['tax_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['tax_meta'][$key])){
                $tax_meta[$key] = $_POST['tax_meta'][$key];
            }
        }
        //save the option array
        update_option( "taxonomy_$t_id", $tax_meta );
    }
}

################################################################################
// Setup Columns for Neighborhoods
################################################################################
add_filter( 'manage_neighborhoods_posts_columns', 'rewa_cpt_neighborhood_columns' );
add_action('manage_neighborhoods_posts_custom_column', 'rewa_cpt_neighborhood_custom_column', 10, 2);

/* Shows the Taxonomies in the property list in the admin */
function rewa_cpt_neighborhood_columns($defaults) {
	$defaults['title'] = 'Neighborhood';
	unset($defaults['author']);
    return $defaults;
}
function rewa_cpt_neighborhood_custom_column($column_name, $post_id) {
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

function the_neigh_gallery() {
	$neigh_gallery_id = get_post_meta(get_the_ID(), 'dbt_select', true);
	if ($neigh_gallery_id == 'none') { } else {
		echo '<div class="prop-gallery">';
		echo do_shortcode('[nggallery id='.$neigh_gallery_id.' template=galleryview images=0]');
		echo '</div>';
	 }
}

function the_neigh_city() {
	$get_neigh_city = get_post_meta(get_the_ID(), 'dbt_neigh_city', true);
	$the_neigh_city = $get_neigh_city;
	if ($the_neigh_city == '') { } else {
		echo $the_neigh_city;
	 }
}
function the_neigh_state() {
	$get_neigh_state = get_post_meta(get_the_ID(), 'dbt_neigh_state', true);
	$the_neigh_state = $get_neigh_state;
	if ($the_neigh_state == '') { } else {
		echo $the_neigh_state;
	 }
}
function the_neigh_zip() {
	$get_neigh_zip = get_post_meta(get_the_ID(), 'dbt_neigh_zip', true);
	$the_neigh_zip = $get_neigh_zip;
	if ($the_neigh_zip == '') { } else {
		echo $the_neigh_zip;
	 }
}
function neighborhoods_posts_per_page($query) {
    if ( isset($query->query_vars['post_type']) == 'neighborhoods' ) $query->query_vars['posts_per_page'] = 5;
    return $query;
}
if ( !is_admin() ) add_filter( 'pre_get_posts', 'neighborhoods_posts_per_page' );

################################################################################
// Remove quick edit for Neighborhoods
################################################################################
add_filter( 'post_row_actions', 'remove_neighborhoods_row_actions', 10, 2 );
function remove_neighborhoods_row_actions( $actions, $post )
{
  global $current_screen;
	if( $current_screen->post_type != 'neighborhoods' ) return $actions;
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

add_filter( 'template_include', 'include_neighborhoods_template', 1 );

function include_neighborhoods_template( $template_path ) {
    if ( get_post_type() == 'neighborhoods' ) {
    	// Single Neighborhood Template
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-neighborhoods.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '../templates/single-neighborhoods.php';
            }
        }

        // Archive Template
        if ( is_archive() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'archive-neighborhoods.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '../templates/archive-neighborhoods.php';
            }
        }

        // Neighborhood Taxonomy Archive Template
		if ( is_tax('property-type')) {
            if ( $theme_file = locate_template( array ( 'taxonomy-neighborhood-category.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '../templates/taxonomy-neighborhood-category.php';
            }
		}



    }
    return $template_path;
}


