<?php
get_header(); ?>
<div class="content section-inner">		
<link rel="stylesheet" type="text/css" href="wp-content/themes/urstein/home.css" media="screen" />
	<?php while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div id="home-content" class="post-container">
			<?php if ( has_post_thumbnail() ) : ?>			
				<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); $thumb_url = $thumb['0']; ?>
				<div class="featured-media">
					<?php the_post_thumbnail('post-image'); ?>
				</div> <!-- /featured-media -->
			<?php endif; ?>
			<div class="post-inner">				    
			    <div class="post-content">
			    	<?php the_content(); ?>
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __( 'Pages:', 'urstein' ) . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>
			    </div> <!-- /post-content -->
			    <div class="clear"></div>
			    <?php edit_post_link(__('Edit Page','urstein'), '<div class="post-meta"><p class="post-edit">', '</p></div>'); ?>
			</div> <!-- /post-inner -->			<!-- TODO: News, Anschlag, Lager Feed -->			</div> <!-- /post-container -->
		</div> <!-- /post -->
		
	<?php endwhile; ?>

	<div class="clear"></div>
	
</div> <!-- /content -->
								
<?php get_footer(); ?>