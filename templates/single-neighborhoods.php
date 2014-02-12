<?php get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">


	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="back-to">
			<a href="<?php echo home_url(); ?>/neighborhoods">&laquo; Back to Neighborhoods</a>
		</div>

			<h1 class="pagetitle"><?php the_title(); ?></h1>


				<?php the_content(); ?>



	<?php endwhile; else: ?>

		<p>Sorry, this neighborhood does not exist.</p>

<?php endif; ?>

  </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

