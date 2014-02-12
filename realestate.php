<?php
/*
Plugin Name: Real Estate by ReWebApps
Plugin URI: http://www.realestatewebapps.com
Description: A custom Real Estate plugin that offers properties, neighborhoods, agents, and testimonials. Requires the NextGen-Gallery plugin for property images.
Version: 1.5.6
Author: ReWebApps
Author URI: http://www.realestatewebapps.com
License: GPL3
*/

// Load Properties Module
require_once('modules/properties.php');

// Load Neighborhoods Module
require_once('modules/neighborhoods.php');

// Load Agents Module
require_once('modules/agents.php');

// Load Presstrends
require_once('modules/presstrends.php');


################################################################################
// Plugin Discontinued Notice
################################################################################
function rewebapp_notice_properties() {
	echo '<div class="updated" style="padding:10px !important;"><strong>NOTICE:</strong> The <strong>Real Estate by ReWebApps</strong> plugin is no longer being supported. The plugin is currently used to display your <strong>Properties/Neighborhoods/Agents/Testimonials</strong>. Please start migrating to an IDX service or another WordPress Plugin solution. Please contact your <strong>Designer and/or Developer</strong> if you have any questions. Any galleries created with <strong>NextGen-Gallery Plugin</strong> will NOT be removed when the plugin is uninstalled. We recommend you first backup your data please use the <a href="/wp-admin/export.php">WordPress Export Tool</a>.</div>';
}

add_action('wp_dashboard_setup', 'rewebapp_notice_properties');
add_filter( 'views_edit-properties', 'rewebapp_notice_properties' );
add_filter( 'views_edit-neighborhoods', 'rewebapp_notice_properties' );
add_filter( 'views_edit-agents', 'rewebapp_notice_properties' );
add_filter( 'views_edit-testimonials', 'rewebapp_notice_properties' );



################################################################################
// Uninstall
################################################################################
register_uninstall_hook( __FILE__, 'rewebapps_uninstall');

function rewebapps_uninstall(){
    global $wpdb;
    $table = $wpdb->prefix."posts";
    $wpdb->query("DELETE FROM $table WHERE post_type='properties'");
    $wpdb->query("DELETE FROM $table WHERE post_type='neighborhoods'");
    $wpdb->query("DELETE FROM $table WHERE post_type='agents'");
    $wpdb->query("DELETE FROM $table WHERE post_type='testimonials'");

    $table = $wpdb->prefix."postmeta";
    // Properties
    $wpdb->query("DELETE FROM $table WHERE meta_key='dbt_neighborhood_select'");
    $wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_agent_select'");
    $wpdb->query("DELETE FROM $table WHERE meta_key='dbt_select'"); // ngg-gallery
    $wpdb->query("DELETE FROM $table WHERE meta_key='dbt_list_price'");
    $wpdb->query("DELETE FROM $table WHERE meta_key='dbt_list_date'");
    $wpdb->query("DELETE FROM $table WHERE meta_key='dbt_sold_price'");
    $wpdb->query("DELETE FROM $table WHERE meta_key='dbt_sold_date'");
    $wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_address'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_city'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_state'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_zip'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_mls_id'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_bed'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_bath'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_half_bath'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_garage'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_living_space'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_land_size'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_virtual_tour'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_use_latlong'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_latitude'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_longitude'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_country'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_prop_size_metric'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_weekly_rental_high_season'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_weekly_rental_low_season'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_monthly_rental_high_season'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_monthly_rental_low_season'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_hide_price'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_list_currency'");


	// Neighborhods
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_neigh_city'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_neigh_state'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_neigh_zip'");

	// Agents
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_position'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_email'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_office_number'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_mobile_number'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_fax_number'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_website'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_facebook'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_twitter'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_linkedin'");
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbta_agent_youtube'");

	// Testimonials
	$wpdb->query("DELETE FROM $table WHERE meta_key='dbt_testimonials_agent_select'");

	$table = $wpdb->prefix."term_taxonomy";
	$wpdb->query("DELETE FROM $table WHERE taxonomy='property-status'");
	$wpdb->query("DELETE FROM $table WHERE taxonomy='property-type'");
	$wpdb->query("DELETE FROM $table WHERE taxonomy='neighborhood-category'");
}

