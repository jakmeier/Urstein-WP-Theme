<?php get_header(); 
	$args = array(
		'post_type' => 'fact',
		'posts_per_page' => -1,
		'order'     => 'ASC',
		'orderby'   => 'post_title',
	);
	$facts = get_posts($args);

?>
<div class="content section-inner">		
	<style>
		.fact-list{
			display: grid;
			grid-template-columns: max-content auto;
			grid-gap: 20px;
		}
	</style>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">
			    <div class="post-content">
					<div class="fact-list">
						<?php 
							foreach ($facts as $fact){
								echo '<div class="fact-title">' . esc_html($fact->post_title) . '</div>';
								echo '<div class="fact-content">' . nl2br(esc_html($fact->post_content)) . '<br>';
								if ( has_post_thumbnail($fact->ID) ){
									echo get_the_post_thumbnail($fact->ID, 'medium');
								}
								echo '</div>';
						 } ?>
					</div> <!-- fact-list --> 
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