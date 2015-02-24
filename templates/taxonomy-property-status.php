<?php get_header(); ?>








	<div id="primary" class="site-content">
		<div id="content" role="main">


	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<h1 class="pagetitle"><?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name; ?></h1>

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



	<?php  endwhile; else: ?>

		<p>Sorry, this property no longer exists.</p>

<?php endif; ?>

				<!-- pagintation -->
				<div id="pagination" class="clearfix">
					<div class="past-page"><?php previous_posts_link( 'newer' ); ?></div>
					<div class="next-page"><?php next_posts_link( 'older' ); ?></div>
				</div>
				<!-- pagination -->

	</div>
	</div>



<?php get_sidebar(); ?>
<?php get_footer(); ?>