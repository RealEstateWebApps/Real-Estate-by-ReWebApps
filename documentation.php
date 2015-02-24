<style>

	code {
		background: #000;
		color: #fff;
	}

</style>


<div class="wrap">

<div class="error" style="padding:10px !important;margin-top:20px;"><strong>ACTION REQUIRED:</strong> Please start migrating to an IDX service such as <a href="https://signup.idxbroker.com/d/imforza">IDX Broker Platinum</a> or another solution to manage your properties.</div>

<div class="updated" style="padding:10px !important;"><strong>NOTICE:</strong> The <strong>Real Estate by ReWebApps</strong> plugin is no longer being supported. The plugin is currently used to display your <strong>Properties/Neighborhoods/Agents</strong>. Please start migrating to an IDX service such as <a href="https://signup.idxbroker.com/d/imforza">IDX Broker Platinum</a> or another solution. Please contact your <strong>Designer and/or Developer</strong> if you have any questions. Any galleries created with <strong>NextGen-Gallery Plugin</strong> will NOT be removed when the plugin is uninstalled. We recommend you first backup your data please use the <a href="/wp-admin/export.php">WordPress Export Tool</a>.</div>

<h2>Real Estate by ReWebApps</h2>



<br />
<hr>

<h2>Developer Documentation</h2>

<h4>Properties</h4>
<p>The following is a list of functions that can be used to display property information within your theme templates.</p>

<code>
the_prop_gallery();
the_list_price();
the_list_date();
the_sold_price();
the_sold_date();
the_prop_neighborhood();
the_prop_agent();
the_prop_address();
the_prop_city();
the_prop_currency();
the_prop_state();
the_prop_country();
the_prop_zip();
the_mls_id();
the_prop_bed();
the_prop_bath();
the_prop_half_bath();
the_prop_garage();
the_prop_living_space();
the_prop_size_metric();
the_prop_land_size();
the_prop_virtual_tour();
the_prop_latitude();
the_prop_longitude();
the_property_map();
</code>

<p>Need to display properties by a specific property type and status? You can do so by formatting your urls as follows:</p>

<pre><?php bloginfo('url')?>/properties/?property-type=TYPENAME&property-status=STATUSNAME</pre>

So for example if you have a property type of land and a status of sold you can display those specific properties with the following url:

<pre><?php bloginfo('url')?>/properties/?property-type=land&property-status=sold</pre>

<p>Note: Please make sure you use the slug name for your property type and status. If you wish to exclude a property type or status just use != instead of = within the url structure.</p>

<hr>

<h4>Agents</h4>
<p>The following is a list of functions that can be used to display agent information within your theme templates.</p>

<code>
the_agent_position();
the_agent_email();
the_agent_office_number();
the_agent_mobile_number();
the_agent_fax_number();
the_agent_website();
the_agent_facebook();
the_agent_twitter();
the_agent_linkedin();
the_agent_youtube();
the_agent_properties();
</code>

<hr>

<h4>Neighborhoods</h4>
<p>The following is a list of functions that can be used to display neighborhoods information within your theme templates.</p>

<code>
the_neigh_gallery();
the_neigh_city();
the_neigh_state();
the_neigh_zip();
</code>

</div>