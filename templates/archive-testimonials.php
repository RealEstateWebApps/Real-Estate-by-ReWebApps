<?php get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">


			<h1 class="pagetitle"><?php post_type_archive_title() ?></h1>
       
				<?php $posts=query_posts($query_string . '&orderby=menu_order&order=ASC&showposts=-1'); 
				if (have_posts()) : ?>
				<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
				<?php if (is_post_type_archive()) { ?>
				
				<?php } ?>
				<?php while (have_posts()) : the_post(); ?>
				<div class="testimonials">
					<div class="testimonials-thumb">
						<a href="<?php the_permalink() ?>">
						<?php if ( has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('testimonial-list-thumb'); ?>
						<?php else : ?>
							<img src="http://placehold.it/100x140&text=No+Photo+Available" width="100" height="140" alt="<?php the_title(); ?>" />
						<?php endif; ?>
						</a>
					</div>
					<div class="testimonials-desc">
					<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>"><?php the_title(); ?></a></h3>
	
							<?php the_excerpt(); ?>
			
							<p><?php the_testimonials_agent(); ?></p>
					</div>
					<div class="clear"></div>
				</div>
				<?php endwhile; ?>
				
				<?php else : 
				echo("<h2 class='center'>".__('No posts found.', 'kubrick').'</h2>');
				get_search_form();
				endif;
				?>

		</div>
	</div>


<?php get_sidebar(); ?>
<?php get_footer(); ?>
