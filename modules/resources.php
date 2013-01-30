<?php

################################################################################
// Setup Meta Boxes
################################################################################
$res_prefix = 'res_';

$res_meta_box = array(
    'id' => 'res-meta-box',
    'title' => 'Resource Options',
    'page' => 'resources',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Type of Resource',
            'id' => $res_prefix . 'type',
            'type' => 'select',
            'std' => '',
            'options' => array('Company', 'Organization', 'Contractor')
        ),
        array(
            'name' => 'Contact First Name',
            'id' => $res_prefix . 'first_name',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Contact Last Name',
            'id' => $res_prefix . 'last_name',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Toll Free Number',
            'id' => $res_prefix . 'toll_free',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Phone Number',
            'id' => $res_prefix . 'phone_number',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Fax Number',
            'id' => $res_prefix . 'fax',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Website',
            'id' => $res_prefix . 'website',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Address',
            'id' => $res_prefix . 'address',
            'type' => 'text_address',
            'std' => ''
        ),
        array(
            'name' => 'City',
            'id' => $res_prefix . 'city',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'State',
            'id' => $res_prefix . 'state',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Zip Code',
            'id' => $res_prefix . 'zip',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Facebook',
            'id' => $res_prefix . 'facebook',
            'type' => 'text_social',
            'std' => ''
        ),
        array(
            'name' => 'Twitter',
            'id' => $res_prefix . 'twitter',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'LinkedIn',
            'id' => $res_prefix . 'linkedin',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'YouTube',
            'id' => $res_prefix . 'youtube',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Yelp',
            'id' => $res_prefix . 'yelp',
            'type' => 'text',
            'std' => ''
        )  
    )
);

add_action('admin_menu', 'res_add_box');

// Add meta box
function res_add_box() {
    global $res_meta_box;

    add_meta_box($res_meta_box['id'], $res_meta_box['title'], 'res_show_box', $res_meta_box['page'], $res_meta_box['context'], $res_meta_box['priority']);
}






// Callback function to show fields in meta box
function res_show_box() {
    global $res_meta_box, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="res_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($res_meta_box['fields'] as $field) {
        // get current post meta data
        $res_meta = get_post_meta($post->ID, $field['id'], true);

        switch ($field['type']) {
            case 'text':
            	echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $res_meta ? $res_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);
				echo     '<td>',
            '</tr>';
			break;
			case 'text_address':
            	echo '<tr>',
                '<tr><td colspan="2"><hr style="background:#ddd; border:0px; height:1px; position:relative; width:100%;" /><h4>Resource Location</h4></td></tr>','<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $res_meta ? $res_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);
				echo     '<td>',
            '</tr>';
			break;
			case 'text_social':
            	echo '<tr>',
                '<tr><td colspan="2"><hr style="background:#ddd; border:0px; height:1px; position:relative; width:100%;" /><h4>Social Media Profiles</h4></td></tr>','<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $res_meta ? $res_meta : $field['std'], '" size="20" style="width:20%; min-width:150px;" />', '<br />', isset($field['desc']);
				echo     '<td>',
            '</tr>';
			break;
			case 'select':
			echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option', $res_meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                echo     '<td>',
            '</tr>';
             break;
                
        }
      
    }

    echo '</table>';
}

################################################################################
// Save Data from Meta Box
################################################################################
add_action('save_post', 'res_save_data');

// Save data from meta box
function res_save_data($post_id) {
    global $res_meta_box;

    // verify nonce
    if (!wp_verify_nonce( isset($_POST['res_meta_box_nonce']), basename(__FILE__))) {
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

    foreach ($res_meta_box['fields'] as $field) {
        $res_old = get_post_meta($post_id, $field['id'], true);
        $res_new = $_POST[$field['id']];

        if ($res_new && $res_new != $res_old) {
            update_post_meta($post_id, $field['id'], $res_new);
        } elseif ('' == $res_new && $res_old) {
            delete_post_meta($post_id, $field['id'], $res_old);
        }
    }
}




################################################################################
// Create Resources Custom Post Type
################################################################################
add_action('init', 'resources_register');
 
function resources_register() {
 
	$labels = array(
		'name' => _x('Resources', 'post type general name'),
		'singular_name' => _x('Resource', 'post type singular name'),
		'add_new' => _x('Add New', 'Resource'),
		'add_new_item' => __('Add New Resource'),
		'edit_item' => __('Edit Resource'),
		'new_item' => __('New Resource'),
		'view_item' => __('View Resource'),
		'search_items' => __('Search Resources'),
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
		'menu_icon' =>  WP_PLUGIN_URL.  '/RealEstate/images/resources-icon.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','thumbnail', 'tags', 'editor'),
		'has_archive' => true
	  ); 
	  
################################################################################
// Register Taxonomy
################################################################################
 	register_taxonomy("res-category", array("resources"), array("hierarchical" => true, "label" => "Resource Category", "rewrite" => array('slug' => 'res-category'), "query_var" => true));
	register_post_type( 'resources' , $args );
	
}


################################################################################
// Create columns for Resources Custom Post Type
################################################################################
add_filter( 'manage_resources_posts_columns', 'ilc_cpt_res_columns' );
add_action('manage_resources_posts_custom_column', 'ilc_cpt_res_custom_column', 10, 2);

### CUSTOM FIELDS FOR res-category  
//add extra fields to category edit form hook
################################################################################
// Load Javascript
################################################################################
function res_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('my-upload', WP_PLUGIN_URL.'/RealEstate/js/resources.min.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('my-upload');
}

################################################################################
// Load CSS
################################################################################
function res_admin_styles() {
wp_enqueue_style('thickbox');
}
 
################################################################################
// 
################################################################################
if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'res-category') {
add_action('admin_print_scripts', 'res_admin_scripts');
add_action('admin_print_styles', 'res_admin_styles');
}

add_action ( 'res-category_edit_form_fields', 'extra_taxonomy_fields');
//add extra fields to category edit form callback function
function extra_taxonomy_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $tax_meta = get_option( "taxonomy_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Rescource Category Image'); ?></label></th>
<td>
<?php echo $tax_meta['img'] ? '<img src="'.$tax_meta['img'].'" style="float:left; margin-right:10px;" class="upload_image" />' : ''; ?><input type="text" name="tax_meta[img]" id="tax_meta[img]" class="upload_image" size="3" style="width:60%;" value="<?php echo $tax_meta['img'] ? $tax_meta['img'] : ''; ?>"><br /><input id="upload_image_button" type="button" value="Upload Image" style="width:20%;" />
        </td>
</tr>
<?php
}
add_action ( 'edited_res-category', 'save_extra_taxonomy_fileds');
   // save extra category extra fields callback function
function save_extra_taxonomy_fileds( $term_id ) {
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
// Custom Columns for Resources
################################################################################
function ilc_cpt_res_columns($defaults) {
	$defaults['title'] = 'Resource Name';
	unset($defaults['author']);
    $defaults['res-category'] = 'Resource Category';
    return $defaults;
}
function ilc_cpt_res_custom_column($column_name, $post_id) {
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
/* Template Functions */
function the_res_type() {
	$get_res_type = get_post_meta(get_the_ID(), 'res_type', true);
	$the_res_type = $get_res_type;
	if ($the_res_type == '') { } else { 
		echo '<li class="resource-type"><strong>Type: </strong>'.$the_res_type.'</li>';
	 }
}
function the_res_first_name() {
	$get_res_first_name = get_post_meta(get_the_ID(), 'res_first_name', true);
	$the_res_first_name = $get_res_first_name;
	if ($the_res_first_name == '') { } else { 
		echo $the_res_first_name;
	 }
}
function the_res_last_name() {
	$get_res_last_name = get_post_meta(get_the_ID(), 'res_last_name', true);
	$the_res_last_name = $get_res_last_name;
	if ($the_res_last_name == '') { } else { 
		echo $the_res_last_name;
	 }
}
function the_res_name() {
	$get_res_first_name = get_post_meta(get_the_ID(), 'res_first_name', true);
	$the_res_first_name = $get_res_first_name;
	$get_res_last_name = get_post_meta(get_the_ID(), 'res_last_name', true);
	$the_res_last_name = $get_res_last_name;
	if ($the_res_first_name == '' && $the_res_last_name == '') { } else { 
	echo '<li class="resource-name"><strong>Contact: </strong>';
	the_res_first_name();
	echo ' ';
	the_res_last_name();
	echo '</li>';
	}
}
function the_res_toll_free() {
	$get_res_toll_free = get_post_meta(get_the_ID(), 'res_toll_free', true);
	$the_res_toll_free = $get_res_toll_free;
	if ($the_res_toll_free == '') { } else { 
		echo '<li class="resource-toll-free"><strong>Toll Free:</strong> '.$the_res_toll_free.'</li>';
	 }
}
function the_res_phone() {
	$get_res_phone = get_post_meta(get_the_ID(), 'res_phone_number', true);
	$the_res_phone = $get_res_phone;
	if ($the_res_phone == '') { } else { 
		echo '<li class="resource-phone"><strong>Phone: </strong>'.$the_res_phone.'</li>';
	 }
}
function the_res_fax() {
	$get_res_fax = get_post_meta(get_the_ID(), 'res_fax', true);
	$the_res_fax = $get_res_fax;
	if ($the_res_fax == '') { } else { 
		echo '<li class="resource-fax"><strong>Fax: </strong>'.$the_res_fax.'</li>';
	 }
}
function the_res_website() {
	$get_res_website = get_post_meta(get_the_ID(), 'res_website', true);
	$the_res_website = $get_res_website;
	if ($the_res_website == '') { } else { 
		echo '<li class="resource-website"><a href="'.$the_res_website.'" target="_blank">View Website</a></li>';
	 }
}
function the_res_address() {
	$get_res_address = get_post_meta(get_the_ID(), 'res_address', true);
	$the_res_address = $get_res_address;
	if ($the_res_address == '') { } else { 
		echo $the_res_address;
	 }
}
function the_res_city() {
	$get_res_city = get_post_meta(get_the_ID(), 'res_city', true);
	$the_res_city = $get_res_city;
	if ($the_res_city == '') { } else { 
		echo $the_res_city;
	 }
}
function the_res_state() {
	$get_res_state = get_post_meta(get_the_ID(), 'res_state', true);
	$the_res_state = $get_res_state;
	if ($the_res_state == '') { } else { 
		echo $the_res_state;
	 }
}
function the_res_zip() {
	$get_res_zip = get_post_meta(get_the_ID(), 'res_zip', true);
	$the_res_zip = $get_res_zip;
	if ($the_res_zip == '') { } else { 
		echo $the_res_zip;
	 }
}
function the_res_full_address() { 
	$res_address = get_post_meta(get_the_ID(), 'res_address', true);
	$res_city = get_post_meta(get_the_ID(), 'res_city', true);
	$res_state = get_post_meta(get_the_ID(), 'res_state', true);
	$res_zip = get_post_meta(get_the_ID(), 'res_zip', true);
	if ($res_address == '' && $res_city == '' && $res_state == '' && $res_zip == '') { } else {
	echo '<li class="res-full-address">';
	the_res_address();
	echo '<br />';
	the_res_city(); 
	echo ', ';
	 the_res_state(); 
	echo ' '; 
	the_res_zip();
	echo '</li>';
	}
}
function the_res_facebook() {
	$get_res_facebook = get_post_meta(get_the_ID(), 'res_facebook', true);
	$the_res_facebook = $get_res_facebook;
	if ($the_res_facebook == '') { } else { 
		echo '<li class="res-facebook"><a href="',$the_res_facebook,'" target="_blank">Like Me</a></li>';
	 }
}
function the_res_twitter() {
	$get_res_twitter = get_post_meta(get_the_ID(), 'res_twitter', true);
	$the_res_twitter = $get_res_twitter;
	if ($the_res_twitter == '') { } else { 
		echo '<li class="res-twitter"><a href="',$the_res_twitter,'" target="_blank">Follow Me</a></li>';
	 }
}
function the_res_linkedin() {
	$get_res_linkedin = get_post_meta(get_the_ID(), 'res_linkedin', true);
	$the_res_linkedin = $get_res_linkedin;
	if ($the_res_linkedin == '') { } else { 
		echo '<li class="res-linkedin"><a href="',$the_res_linkedin,'" target="_blank">Recommend Me</a></li>';
	 }
}
function the_res_youtube() {
	$get_res_youtube = get_post_meta(get_the_ID(), 'res_youtube', true);
	$the_res_youtube = $get_res_youtube;
	if ($the_res_youtube == '') { } else { 
		echo '<li class="res-youtube"><a href="',$the_res_youtube,'" target="_blank">Watch my Videos</a></li>';
	 }
}
 function the_res_yelp() {
	$get_res_yelp = get_post_meta(get_the_ID(), 'res_yelp', true);
	$the_res_yelp = $get_res_yelp;
	if ($the_res_yelp == '') { } else { 
		echo '<li class="res-yelp"><a href="',$the_res_yelp,'" target="_blank">See my Reviews</a></li>';
	 }
}  

function the_res_map() {
	$get_res_address = get_post_meta(get_the_ID(), 'res_address', true);
	$the_res_address = $get_res_address;
	if ($the_res_address == '') { } else { 
?>
<div id="map_canvas" style="width: 652px; height: 257px; text-align:left; vertical-align:center; margin: 0 auto;"></div> 
			
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript">	

	var geocoder;
  var map;
  var address ="<?php the_res_address(); ?>, <?php the_res_city(); ?>, <?php the_res_state(); ?> <?php the_res_zip(); ?>";
  function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
      zoom: 15,
      center: latlng,
    mapTypeControl: true,
    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
    navigationControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    if (geocoder) {
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          map.setCenter(results[0].geometry.location);

            var infowindow = new google.maps.InfoWindow(
                { content: '<b>'+address+'</b>',
                  size: new google.maps.Size(150,50)
                });
    
            var marker = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map, 
                title:address
            }); 
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });

          } else {
            alert("No results found");
          }
        } else {
          alert("Geocode was not successful for the following reason: " + status);
        }
      });
    }
  }
  jQuery("document").ready(function(){
  	initialize();
  });
		</script>		
<?php
}}


################################################################################
// Remove quick edit for resources
################################################################################
add_filter( 'post_row_actions', 'remove_resources_row_actions', 10, 2 );
function remove_resources_row_actions( $actions, $post )
{
  global $current_screen;
	if( $current_screen->post_type != 'resources' ) return $actions;
	//unset( $actions['edit'] );
	//unset( $actions['view'] );
	//unset( $actions['trash'] );
	unset( $actions['inline hide-if-no-js'] );
	//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );

	return $actions;
}
?>