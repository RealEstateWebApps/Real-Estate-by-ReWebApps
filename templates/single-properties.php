<?php get_header(); ?>

  
	<div id="primary" class="site-content">
		<div id="content" role="main">


	<?php if (have_posts()) : while (have_posts()) : the_post(); 
			$prop_gallery_id = get_post_meta(get_the_ID(), 'dbt_select', true);
			?>
			
			<h1 class="pagetitle"><?php the_title(); ?></h1>
			
			<?php if (isset($_GET['photos'])) : ?>
			<div class="back-to"><a href="<?php the_permalink(); ?>">Back to Details</a></div>
			<?php echo do_shortcode('[nggallery id='.$prop_gallery_id.']'); ?>
			<?php else : ?>

				<br />

				<h3><?php the_prop_address(); ?></h3>
				<?php the_prop_city(); ?>, <?php the_prop_state(); ?> <?php the_prop_zip(); ?><br />		



			<?php the_prop_gallery(); ?>
		

				<ul>
					<li class="pdb-item-title">Property Status</li><li class="pdb-item"><?php echo get_the_term_list( get_the_ID(), 'property-status', ' ', ', ', '' ); ?></li>
					<li class="pdb-item-title">Property Type</li><li class="pdb-item"><?php echo get_the_term_list( get_the_ID(), 'property-type', '', ', ', '' ); ?></li>
					<?php the_prop_neighborhood(); ?>
					<?php the_list_price(); ?>
					<?php the_list_date(); ?>
					<?php the_sold_price(); ?>
					<?php the_sold_date(); ?>
					<?php the_mls_id(); ?>
					<?php the_prop_bed(); ?>
					<?php the_prop_bath(); ?>
					<?php the_prop_half_bath(); ?>
					<?php the_prop_garage(); ?>
					<?php the_prop_living_space(); ?>
					<?php the_prop_land_size(); ?>
					<?php the_prop_agent(); ?>
					<li class="clear"></li>
				</ul>
				<?php the_prop_virtual_tour(); ?>


				<?php the_content(); ?>
	
	
			
		<script type="text/javascript">	
var geocoder;var map;var address="<?php the_prop_address(); ?>, <?php the_prop_city(); ?>, <?php the_prop_state(); ?> <?php the_prop_zip(); ?>";function initialize(){geocoder=new google.maps.Geocoder();var latlng=new google.maps.LatLng(-34.397,150.644);var myOptions={zoom:15,center:latlng,mapTypeControl:true,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU},navigationControl:true,mapTypeId:google.maps.MapTypeId.ROADMAP};map=new google.maps.Map(document.getElementById("map_canvas"),myOptions);if(geocoder){geocoder.geocode({'address':address},function(results,status){if(status==google.maps.GeocoderStatus.OK){if(status!=google.maps.GeocoderStatus.ZERO_RESULTS){map.setCenter(results[0].geometry.location);var infowindow=new google.maps.InfoWindow({content:'<b><?php the_prop_address(); ?><br /> <?php the_prop_city(); ?>, <?php the_prop_state(); ?> <?php the_prop_zip(); ?></b>',size:new google.maps.Size(150,50)});var marker=new google.maps.Marker({position:results[0].geometry.location,map:map,title:address});google.maps.event.addListener(marker,'click',function(){infowindow.open(map,marker)})}else{alert("No results found")}}else{alert("Geocode was not successful for the following reason: "+status)}})}}jQuery("document").ready(function(){initialize()});
		</script>

		
		
	<?php endif; endwhile; else: ?>

		<p>Sorry, this property no longer exists.</p>

<?php endif; ?>

	</div>
	</div>

<?php 
    wp_register_script('google-maps-api', '//maps.googleapis.com/maps/api/js?sensor=false', '', null , true);
    wp_enqueue_script('google-maps-api');
    
 ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
