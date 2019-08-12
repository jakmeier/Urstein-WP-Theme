<?php get_header(); ?>
<style>
	.leaflet-map {height: 400px !important;}
	.leaflet-popup .leaflet-popup-content-wrapper, .leaflet-popup-tip {
		background: none;
		color: #000;
		box-shadow: none;
		font-weight: bolder;
		font-size: larger;
	}
	.leaflet-popup .leaflet-popup-tip-container{display:none}
</style>
<div class="content section-inner">		
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<img src="<?php echo get_template_directory_uri() ?>/images/Logo_weiss1.png"/>
			</div>
			<div class="post-inner">
      
      <section class="anniversary"> <!-- Jubiläum -->
 
            

          <div class="age">
            <p>Die Pfadi Urstein gibt es schon seit:</p>
            <p id="age">99 Jahre</p>
          <div>
        </section> <!-- Jubiläum -->
      
			    <div class="post-content">
					
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="featured-media">		
									<?php the_post_thumbnail('medium'); ?>					
						</div> <!-- /featured-media -->
					<?php endif; ?>
				
			    	<?php /*echo nl2br(esc_html(get_the_content())); */?>
			    	<?php the_content(); ?>
			    	<?php wp_link_pages('before=<div class="clear"></div><p class="page-links">' . __( 'Pages:', 'urstein' ) . ' &after=</p>&seperator= <span class="sep">/</span> '); ?>
			    </div> <!-- /post-content -->
			    <div class="clear"></div>
			    <?php edit_post_link(__('Text bearbeiten','urstein'), '<div class="post-meta"><p class="post-edit">', '</p></div>'); ?>
			</div> <!-- /post-inner -->
			</div> <!-- /post-container -->
		</div> <!-- /post -->
	<?php endwhile; else: ?>
		<p><?php _e("Leider konnte die gesuchte Seite nicht gefunden werden.", "urstein"); ?></p>
	<?php endif; ?>
	<div class="clear"></div>
</div> <!-- /content -->
<?php get_footer(); ?>leaflet-map