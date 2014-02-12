<?php get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">


			<h1 class="pagetitle"><?php post_type_archive_title() ?></h1>



			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="agents">
					<div class="agents-thumb">
						<a href="<?php the_permalink() ?>">
						<?php if ( has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('agent-list-thumb'); ?>
						<?php else : ?>
							<img src="http://placehold.it/100x140&text=No+Photo+Available" width="100" height="140" alt="<?php the_title(); ?>" />
						<?php endif; ?>
						</a>
					</div>
					<div class="agents-desc">
					<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>"><?php the_title(); ?></a></h3>
						<ul>

							<li class="agent-position"><?php the_agent_position(); ?></li>
							<?php the_agent_office_number(); ?>
							<?php the_agent_mobile_number(); ?>
							<?php the_agent_fax_number(); ?>
						</ul>
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
