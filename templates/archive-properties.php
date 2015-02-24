<?php get_header(); ?>


	<div id="primary" class="site-content">
		<div id="content" role="main">

  <h1 class="pagetitle"><?php post_type_archive_title() ?></h1>


		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


		<a href="<?php the_permalink() ?>"><h2><?php the_title(); ?></h2></a><br />
		<?php the_prop_address(); ?> <?php the_prop_city(); ?>, <?php the_prop_state(); ?> <?php the_prop_zip(); ?>



<?php if ( has_post_thumbnail()) : ?>
	<a href="<?php the_permalink() ?>"><?php the_post_thumbnail(); ?></a>
<?php else : ?>
	<a href="<?php the_permalink() ?>"><img src="http://placehold.it/400x260&text=No+Photo+Available" width="400" height="260" alt="<?php the_title(); ?>" /></a>
<?php endif; ?>



		<ul>
			<?php the_list_price(); ?>
			<?php the_prop_bed(); ?>
			<?php the_prop_bath(); ?>
			<li class="property-status"><strong>Property Status</strong>: <?php echo get_the_term_list( get_the_ID(), 'property-status', ' ', ', ', '' ); ?></li>
			<li><strong>Property Type</strong>: <?php echo get_the_term_list( get_the_ID(), 'property-type', '', ', ', '' ); ?></li>
		</ul>



<?php the_excerpt(); ?>

<hr>

<?php endwhile; ?>

				<!-- pagintation -->
				<div id="pagination" class="clearfix">
					<div class="past-page"><?php previous_posts_link( 'newer' ); ?></div>
					<div class="next-page"><?php next_posts_link( 'older' ); ?></div>
				</div>
				<!-- pagination -->

<?php endif; ?>


		</div>
	</div>


<?php get_sidebar(); ?>
<?php get_footer(); ?>