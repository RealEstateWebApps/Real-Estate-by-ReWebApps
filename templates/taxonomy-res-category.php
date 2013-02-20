<?php get_header(); ?>

<div class="container">
<div class="page-wrapper">

<div class="row">

<div class="span3">
		<?php get_sidebar(); ?>
  </div><!-- end span4 -->

  <div class="span9">
  
        
            <div id="leftpanel">
<?php 
			
				if (have_posts()) : 
				$post = $posts[0]; 
				?>
		<h1 class="pagetitle"><?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name; ?></h1>
 	

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries', 'kubrick')); ?></div>
			<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;', 'kubrick')); ?></div>
		</div>

		<?php while (have_posts()) : the_post(); ?>
		<div class="resource">
				<?php if ( has_post_thumbnail()) : ?>
					<div class="resource-thumb">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>">
					<?php the_post_thumbnail('res-thumb'); ?>
					</a>
					</div>
				<?php endif; ?>		
			<div class="resource-desc">
				<ul>
					<li><h3><?php the_title(); ?></h3></li>
					<?php echo '<li><ul class="res-phone">'; ?>
					<?php the_res_toll_free(); ?>
					<?php the_res_phone(); ?>
					<?php the_res_fax(); ?>
					<?php echo '</ul></li>'; ?>
					<?php the_res_full_address(); ?>
					<?php the_res_website(); ?>
					<li class="resource-read-more"><a href="<?php the_permalink(); ?>">More Information</a></li>
				</ul>
			</div>
			
			<div class="clear"></div>
	    </div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries', 'kubrick')); ?></div>
			<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;', 'kubrick')); ?></div>
		</div>
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
  </div><!-- end span8 -->
  
  
  
  </div>
</div>

  <div class="row">
  <div class="page-wrapper-bottom"></div>
  </div>

</div>
<?php get_footer(); ?>
