<?php get_header(); ?>
<div class="content section-inner">		
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); $thumb_url = $thumb['0']; ?>
				<div class="featured-media">
					<?php the_post_thumbnail('post-image'); ?>
				</div> <!-- /featured-media -->
			<?php endif; ?>
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">
			    <div class="post-content">
			    	<?php the_content(); ?>
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __( 'Pages:', 'urstein' ) . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>
			    </div> <!-- /post-content -->
			    <div class="clear"></div>
			    <?php edit_post_link(__('Edit Page','urstein'), '<div class="post-meta"><p class="post-edit">', '</p></div>'); ?>
			</div> <!-- /post-inner -->
			</div> <!-- /post-container -->
		</div> <!-- /post -->
	<?php endwhile; else: ?>
		<p><?php _e("Leider konnte die gesuchte Seite nicht gefunden werden.", "urstein"); ?></p>
	<?php endif; ?>
	<div class="clear"></div>
</div> <!-- /content -->
<?php get_footer(); ?>