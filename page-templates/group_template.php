<?php /* Template Name: Gruppenseite */ ?>
<?php
	require_once get_template_directory() . "/functions/group_functions.php" ;

	// load CSS
	function group_template_styles() {
		if ( is_page_template( 'page-templates/group_template.php' ) ) {
			wp_enqueue_style( 'group-template', get_template_directory_uri() . '/page-templates/group_template.css' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'group_template_styles' );
	
	get_header(); 
?>
<div class="content section-inner">		
	<?php if (have_posts()) : while (have_posts()) : the_post(); 
				$groupid = get_group_id_by_post($post->ID);
	?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">
			    <div class="post-content">
					<section class="group-text">
						<?php echo wp_get_attachment_image( get_group_thumbnail($groupid), 'medium');	?>
						<?php echo nl2br(esc_html(get_the_content())); ?>
					</section>
					<?php edit_post_link(__('Text bearbeiten','urstein'), '<div class="post-meta"><p class="post-edit">', '</p></div>'); ?>
					
					<?php if ( has_post_thumbnail() ):?>
						<section class="group-media">
							<?php the_post_thumbnail('large'); ?>
						</section>
					<?php endif; ?>
					
					<h2>Gruppenleiter</h2>
					<section class="leaders">
						<?php 
							$leaders = get_leaders($groupid);
							$leaders = array_merge($leaders, get_division_leader(stufe_by_group($groupid)));
							if(is_array($leaders)) {
								foreach($leaders as $leader) {
									set_query_var( 'userid', $leader );
									get_template_part( 'template-parts/user_avatar' );
								}
							}
						?>
					</section>
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