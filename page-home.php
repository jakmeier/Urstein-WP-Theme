<?php
get_header(); ?>
<div class="content section-inner">		
<link rel="stylesheet" type="text/css" href="wp-content/themes/urstein/home.css" media="screen" />
	<?php while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div id="home-content" class="post-container">
			<div class="post-inner">	    
			    <div class="post-content">
				<section class="headline"> <!-- Headline -->
				
					<div class="welcome-text">
						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>
						<?php edit_post_link(__('Text bearbeiten','urstein'), '<p class="post-edit">', '</p>'); ?>
					</div>
					<?php if ( has_post_thumbnail() ) : ?>
					<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); $thumb_url = $thumb['0']; ?>
						<div class="main-image">
							<?php the_post_thumbnail('post-image'); ?>
						</div> 
					<?php endif; ?>
			    	
					<div class="quick-links">
						<h2>Direktlinks</h2>
						<div class="quick-links-list">
						<?php 
							$quicklinks = get_post_meta(get_the_id(), 'quicklinks', true); 
							if($quicklinks){
								foreach($quicklinks as $pair){
									$text = $pair[0];
									$link = get_page_link($pair[1]);
									echo '<span><a href="' . $link .'">' . $text .'</a></span><br>';
								}
							}
						?>
						<?php edit_post_link(__('Links bearbeiten','urstein'), '<p class="post-edit">', '</p>'); ?>
						</div>
					</div>
				</section> <!-- /Headline -->
				
				<section> <!-- News Feed -->					<?php
						$news_args = array('fields' => 'ids', 'post_type' => array('news'), 'orderby' => array('date' => 'DESC'), 'posts_per_page' => 10 );
						$news = new WP_Query( $news_args );
						
						$groups = groups_with_events();
						$events = array();
						foreach($groups as $id=>$groupname){
							$event = get_next_event($id);
							if($event) {
								array_push($events, $event->ID);
							}
						}

						$displayIDs = array_merge($news->posts, $events);

						$args = array('post__in' => $displayIDs, 'post_type' => array('news', 'event'), 'orderby' => array('date' => 'DESC'));
						$loop = new WP_Query($args);
						
						while ( $loop->have_posts() ) : $loop->the_post();
							if($post->post_type == 'event') {
								$meta = get_post_meta($post->ID);
								$place = esc_html($meta['place'][0]);
								$bring = esc_html($meta['bring'][0]);
								$description = nl2br(esc_html($meta['description'][0]));
								$datetime = date( 'j.n. G:i', strtotime($meta['start_time'][0]) );
								$groupnames = get_groups_of_event($post->ID);
								
								$content =  'Gruppen: ' . implode(', ', $groupnames). 
											'<br>' . $datetime . ' Uhr, ' . $place .
											'<br>Mitnehmen: ' . $bring .
											'<br>Weitere Infos: ' . $description .
											'<p><a href="' . get_permalink($post->ID) .'">Zur Anmeldung</a></p>';
							} else {
								$content = get_the_content();
							}
						?>
						<article>
							<div class="entry-image">
								<?php if ( has_post_thumbnail() ){
									//$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); 
									//$thumb_url = $thumb['0']; 
									the_post_thumbnail('thumbnail');
								} elseif($post->post_type == 'event') {
									echo wp_get_attachment_image( get_group_thumbnail(array_keys($groupnames)), 'thumbnail');
								}
								elseif($post->post_type == 'news') {
									echo wp_get_attachment_image( get_theme_mod('urstein_custom_img_news'), 'thumbnail');
								}								
								?>
							</div>
							<div class ="entry-text">
							<h3><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title();?></a></h3>
								<div class="entry-content">
									<?php echo $content;?>
									<?php edit_post_link(__('Beitrag bearbeiten','urstein'), '<p class="post-edit">', '</p>'); ?>
								</div>
							</div>
						</article>
						
						<?php
						endwhile;
					?>
				</section> <!-- /News Feed -->
			    </div> <!-- /post-content -->
			    <div class="clear"></div>
			    
				
				
			</div> <!-- /post-inner -->			</div> <!-- /post-container -->
		</div> <!-- /post -->

	<?php endwhile; ?>
	<div class="clear"></div>	
</div> <!-- /content -->
<?php get_footer(); ?>