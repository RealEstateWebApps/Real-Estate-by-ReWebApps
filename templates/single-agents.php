<?php get_header(); ?>


	<div id="primary" class="site-content">
		<div id="content" role="main">


			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<h1><?php the_title(); ?></h1>
				<div class="agent-thumb">
					<?php if ( has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('agent-thumb'); ?>
					<?php else : ?>
						<img src="http://placehold.it/200x240&text=No+Photo+Available" width="200" height="240" alt="<?php the_title(); ?>" />
					<?php endif; ?>
				</div>
				
					<div class="agent-details">
					<ul>
						<?php the_agent_position(); ?>
						<?php the_agent_office_number(); ?>
						<?php the_agent_mobile_number(); ?>
						<?php the_agent_fax_number(); ?>
						<li class="spacer"></li>
						<?php the_agent_email(); ?>
						<?php the_agent_website(); ?>
						<?php the_agent_testimonials(); ?>
						<li class="spacer"></li>
						<li>
							<ul class="agent-social">
								<?php the_agent_facebook(); ?>
								<?php the_agent_twitter(); ?>
								<?php the_agent_linkedin(); ?>
								<?php the_agent_youtube(); ?>
							</ul>
						</li>
						<li class="clear"></li>
					</ul>
				</div>
			
				
				<?php the_content(); ?>
			<?php endwhile; else: ?>
				
				<p>Sorry, This agent appears to no longer exist.</p>
			
			<?php endif; ?>
			


			</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

