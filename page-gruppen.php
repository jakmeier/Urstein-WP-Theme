<?php 
require_once get_template_directory() . "/functions/group_functions.php" ;
get_header();
?>
<link rel="stylesheet" type="text/css" href="../wp-content/themes/urstein/gruppen.css" media="screen" />
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
					<section class="groups-text">
						<?php echo nl2br(get_the_content()); ?>
						<?php edit_post_link(__('Diesen Text bearbeiten','urstein'), '<div class="post-meta"><p class="post-edit">', '</p></div>'); ?>
					</section>
					<section class="group-list">
						<?php
							$groups = groups_with_events();
							if($groups):
							foreach($groups as $id => $group):
						?>
							<article class="group"> 
								<a class="white-link" href="<?php the_group_url($id); ?>">
									<h3><?php echo esc_html($group);?></h3>
								</a>
								<a href="<?php the_group_url($id); ?>">
									<img src="<?php the_group_image_url($id);?>">
								</a>
								<a class="white-link" href="<?php the_group_url($id); ?>">
									<p><?php 
										$description = get_the_group_content($id);
										if(strlen($description) > 200){
											$description = substr($description, 0,180) . "...\r\n> Mehr lesen";
										}
										echo nl2br($description);
									?></p>
								</a>
							</article>
						<?php endforeach; endif;?>
					</section> <!-- /group-list -->
				</div> <!-- /post-content -->
			    <div class="clear"></div>
			    
			</div> <!-- /post-inner -->
			</div> <!-- /post-container -->
		</div> <!-- /post -->
	<?php endwhile; else: ?>
		<p><?php _e("Leider konnte die gesuchte Seite nicht gefunden werden.", "urstein"); ?></p>
	<?php endif; ?>
	<div class="clear"></div>
</div> <!-- /content -->
<?php get_footer(); ?>