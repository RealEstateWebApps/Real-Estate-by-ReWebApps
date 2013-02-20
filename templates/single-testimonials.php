<?php get_header(); ?>

<div class="container">
<div class="page-wrapper">

<div class="row">

<div class="span3">
		<?php get_sidebar(); ?>
  </div><!-- end span4 -->

  <div class="span9">
	
        
            <div id="leftpanel">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<h1><?php the_title(); ?></h1>

				<?php the_content('<p class="serif">' . __('Read the rest of this entry &raquo;', 'kubrick') . '</p>'); ?>

				<p><?php the_testimonials_agent(); ?></p>

	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria.', 'kubrick'); ?></p>

<?php endif; ?>
	</div>







  </div><!-- end span8 -->
  
  
  
  </div>
</div>

  <div class="row">
  <div class="page-wrapper-bottom"></div>
  </div>

</div>
<?php get_footer(); ?>
