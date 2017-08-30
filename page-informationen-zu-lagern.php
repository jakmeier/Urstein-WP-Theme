<?php get_header(); 
	$args = array(
		'post_type' => 'camp',
		'posts_per_page' => 10,
		'order'     => 'DESC',
		'meta_key' => 'start_date',
		'orderby'   => 'meta_value',
		'meta_type' => 'DATETIME'
	);
	$camps = get_posts($args);

?>
<div class="content section-inner">		
<link rel="stylesheet" type="text/css" href="wp-content/themes/urstein/camp.css" media="screen" />
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">
			    <div class="post-content">
					
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="featured-media">		
									<?php the_post_thumbnail('medium'); ?>					
						</div> <!-- /featured-media -->
					<?php endif; ?>

			    	<?php the_content(); ?>
			   
					<div class="clear"></div>
					<?php edit_post_link(__('Text bearbeiten','urstein'), '<div class="post-meta"><p class="post-edit">', '</p></div>'); ?>

					<div class="camp-list">
						<?php 
							foreach ($camps as $camp){
								set_query_var('element_post', $camp);
								get_template_part( 'template-parts/feed_element' );
						 } ?>
					</div> <!-- camp-list --> 
				</div> <!-- /post-content -->
			</div> <!-- /post-inner -->
			</div> <!-- /post-container -->
		</div> <!-- /post -->
	<?php endwhile; else: ?>
		<p><?php _e("Leider konnte die gesuchte Seite nicht gefunden werden.", "urstein"); ?></p>
	<?php endif; ?>
	<div class="clear"></div>
</div> <!-- /content -->
<?php get_footer(); ?>