<?php get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
		
	<?php  if (is_post_type_archive()) { ?>
		<h1 class="pagetitle"><?php post_type_archive_title() ?></h1>
 	<?php } ?>
		
		<?php if (have_posts()) : ?>
		 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>


		<?php $posts=query_posts($query_string . '&orderby=title&order=asc'); 
		  while (have_posts()) : the_post(); ?>
		  
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

		<?php neighborhood_paginate(); ?>
	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='center'>".__("Sorry, but there aren't any posts in the %s category yet.", 'kubrick').'</h2>', single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo('<h2>'.__("Sorry, but there aren't any posts with this date.", 'kubrick').'</h2>');
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='center'>".__("Sorry, but there aren't any posts by %s yet.", 'kubrick')."</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>".__('No posts found.', 'kubrick').'</h2>');
		}
	  get_search_form();
	endif;
?>


	</div>
 </div>
  
  
  
<?php get_sidebar(); ?>
<?php get_footer(); ?>

