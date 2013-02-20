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
$url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$lastPart = parse_url($url); $lastPart = $lastPart[path]; $lastPart = explode("/", $lastPart); $taxonomyname = $lastPart[3];
$args['tax_query'][] = array(
    'taxonomy' => 'property-status',
    'terms' => $taxonomyname,
    'field' => 'slug',
    'operator' => 'IN'
    
);
if ($taxonomyname == 'sold') : 
$args['orderby'] = "date";
else : 
$args['orderby'] = "meta_value_num"; $args['meta_key'] = "dbt_list_price";
endif;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args['paged'] = $paged;
query_posts($args);


if (have_posts()) : ?>
		<h1 class="pagetitle"><?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); echo $term->name; ?></h1>
 	


		<?php while (have_posts()) : the_post(); ?>
		<div class="property">
			<div class="prop-thumb">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>">
				<?php if ( has_post_thumbnail()) : ?>
					<?php the_post_thumbnail('listing-thumb'); ?>
				<?php else : ?>
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/no-image.jpg" width="400" height="260" alt="<?php the_title(); ?>" />
				<?php endif; ?>		
				</a>
			</div>
			<div class="prop-desc">
				<ul>
		      	  	<li><h3><a href="<?php the_permalink() ?>" rel="bookmark" title="More about <?php the_title(); ?>"><?php the_title(); ?></a></h3></li>
		      	  	<li><?php the_prop_address(); ?></li>
					<li><?php the_prop_city(); ?>, <?php the_prop_state(); ?> <?php the_prop_zip(); ?></li>
		      	  	<li class="property-status"><strong>Property Status</strong>: <?php echo get_the_term_list( get_the_ID(), 'property-status', ' ', ', ', '' ); ?></li>
					<li><strong>Property Type</strong>: <?php echo get_the_term_list( get_the_ID(), 'property-type', '', ', ', '' ); ?></li>
					<?php the_list_price(); ?>
					<?php the_prop_bed(); ?>
					<?php the_prop_bath(); ?>
				</ul>
			</div>
			<div class="prop-read-more"><a href="<?php the_permalink(); ?>">View Property Details</a></div>
			<div class="clear"></div>
	    </div>

		<?php endwhile; ?>

		<?php paginate(); ?>
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
	  </div><!-- end span8 -->
  
  
  
  </div>
</div>

  <div class="row">
  <div class="page-wrapper-bottom"></div>
  </div>

</div>
<?php get_footer(); ?>
