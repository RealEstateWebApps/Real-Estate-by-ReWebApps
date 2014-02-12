<?php get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

	<?php  if (is_post_type_archive()) { ?>
		<h1 class="pagetitle"><?php post_type_archive_title() ?></h1>
 	<?php } ?>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		  <h2><?php the_title(); ?></h2>


		<div class="neighborhood">
			<div class="neighborhood-thumb">
				<a href="<?php the_permalink() ?>">
				 <?php if ( has_post_thumbnail()) : ?>
				   <?php the_post_thumbnail('listing-thumb'); ?>
				 <?php else : ?>
				 	<img src="http://placehold.it/400x260&text=No+Photo+Available" width="400" height="260" alt="<?php the_title(); ?>" />
				 <?php endif; ?>
				</a>
			</div>
			<div class="neighborhood-desc">
				<ul>
		      	  	<li><h3><a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>"><?php get_the_title(); ?></a></h3></li>
					<li><?php the_excerpt(); ?></li>
				</ul>

			</div>
			<div class="neighborhood-read-more"><a href="<?php the_permalink(); ?>">View Neighborhood</a></div>
			<div class="clear"></div>
	    </div>

		<?php endwhile; ?>


	<?php else : endif; ?>


	</div>
 </div>



<?php get_sidebar(); ?>
<?php get_footer(); ?>

