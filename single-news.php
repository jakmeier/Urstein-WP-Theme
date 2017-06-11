<?php get_header(); ?>

<div class="content section-inner">
											        
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('single single-post'); ?>>
			
			<div class="post-container">			
				<?php $post_format = get_post_format(); ?>	
				
				<div class="post-header">		
					<p class="post-date"><?php the_time(get_option('date_format')); ?></p>
					<h1 class="post-title"><?php the_title(); ?></h1>
				</div>	
				
				<?php if ( $post_format == 'gallery' ) : ?>
					<div class="featured-media">	
						<?php urstein_flexslider('medium'); ?>
						<div class="clear"></div>
					</div> <!-- /featured-media -->
				<?php elseif ( has_post_thumbnail() ) : ?>
					<div class="featured-media">
						<?php the_post_thumbnail('medium'); ?>
					</div> <!-- /featured-media -->
				<?php endif; ?>
				
				
				<div class="post-inner">
				    <div class="post-content">
				    	<?php the_content(); ?>
				    </div> <!-- /post-content -->
					<?php edit_post_link(__('Beitrag bearbeiten','urstein'), '<p class="post-edit">', '</p>'); ?>
				    <div class="clear"></div>
				</div> <!-- /post-inner -->
			
			</div> <!-- /post-container -->
			
		</div> <!-- /post -->
		
	</div> <!-- /content -->
		
   	<?php endwhile; else: ?>

		<p><?php _e("Leider konnte der Beitrag nicht gefunden werden.", "urstein"); ?></p>
	
	<?php endif; ?>    

</div> <!-- /content -->
		
<?php get_footer(); ?>