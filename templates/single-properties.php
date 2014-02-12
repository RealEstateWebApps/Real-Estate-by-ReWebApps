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

				<?php the_property_map(); ?>



	<?php endif; endwhile; else: ?>

		<p>Sorry, this property no longer exists.</p>

<?php endif; ?>

	</div>
	</div>



<?php get_sidebar(); ?>
<?php get_footer(); ?>
