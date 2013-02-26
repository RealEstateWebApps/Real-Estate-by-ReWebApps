<?php get_header(); ?>

  
	<div id="primary" class="site-content">
		<div id="content" role="main">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<h1><?php the_title(); ?></h1>

				<?php the_content('<p class="serif">' . __('Read the rest of this entry &raquo;', 'kubrick') . '</p>'); ?>

				<p><?php the_testimonials_agent(); ?></p>

	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria.', 'kubrick'); ?></p>

<?php endif; ?>

		</div>
	</div>



<?php get_sidebar(); ?>
<?php get_footer(); ?>
