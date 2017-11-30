<?php get_header(); 
	$args = array(
		'post_type' => 'download',
		'posts_per_page' => -1,
		'order'     => 'ASC',
		'orderby'   => 'post_title',
	);
	$downloads = get_posts($args);

?>
<div class="content section-inner">		
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">
			    <div class="post-content">
					<div class="downlaod-list">
						<?php 
							foreach ($downloads as $download){
								echo '<div class="download-box">';
								echo '<div class="download-title">' . esc_html($download->post_title) . '</div>';
								$file = get_post_meta($download->ID, 'download', true);
								set_query_var('file', $file);
								get_template_part( 'template-parts/download_link' );
								echo '</div>';
								
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