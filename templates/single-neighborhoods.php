<?php get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
        

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="back-to">
			<a href="<?php echo home_url(); ?>/neighborhoods">&laquo; Back to Neighborhoods</a>
		</div>

			<h1 class="pagetitle"><?php the_title(); ?></h1>
			
			<?php $meta = get_post_meta(get_the_ID(), 'dbt_select', true);
				if ($meta == 'none') { } else { 
				echo '<div class="prop-gallery">';
				echo do_shortcode('[nggallery id='.$meta.' template=galleryview images=0]');
				echo '</div>';
			 }
				
				
			?>
				<?php the_content('<p class="serif">' . __('Read the rest of this entry &raquo;', 'kubrick') . '</p>'); ?>
				
				
				
	<?php	
	echo do_shortcode('[lme-module module="market-stats" neighborhood="'.get_the_title().'" city="'.get_post_meta(get_the_ID(), 'dbt_neigh_city', true).'" state="'.get_post_meta(get_the_ID(), 'dbt_neigh_state', true).'" zip="'.get_post_meta(get_the_ID(), 'dbt_neigh_zip', true).'"]');
		echo do_shortcode('[lme-module module="schools" neighborhood="'.get_the_title().'" city="'.get_post_meta(get_the_ID(), 'dbt_neigh_city', true).'" state="'.get_post_meta(get_the_ID(), 'dbt_neigh_state', true).'" zip="'.get_post_meta(get_the_ID(), 'dbt_neigh_zip', true).'"]');
		echo do_shortcode('[lme-module module="yelp" neighborhood="'.get_the_title().'" city="'.get_post_meta(get_the_ID(), 'dbt_neigh_city', true).'" state="'.get_post_meta(get_the_ID(), 'dbt_neigh_state', true).'" zip="'.get_post_meta(get_the_ID(), 'dbt_neigh_zip', true).'"]');
		echo do_shortcode('[lme-module module="walk-score" zip="'.get_post_meta(get_the_ID(), 'dbt_neigh_zip', true).'"]');
	?>

	

	<?php endwhile; else: ?>

		<p>Sorry, this neighborhood does not exist.</p>

<?php endif; ?>

  </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

