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
				
				<section> <!-- News Feed -->
					<h1> Aktuelles </h1>					<?php
						$news_args = array('fields' => 'ids', 'post_type' => array('news'), 'orderby' => array('date' => 'DESC'), 'posts_per_page' => 10 );
						$news = new WP_Query( $news_args );
						
						$camp_args = array('fields' => 'ids', 'post_type' => array('camp'), 'orderby' => array('date' => 'DESC'), 'posts_per_page' => 4 );
						$camps = new WP_Query( $camp_args );
						
						$groups = groups_with_events();
						$events = array();
						if($groups){
							foreach($groups as $id=>$groupname){
								$event = get_next_event($id);
								if($event) {
									array_push($events, $event->ID);
								}
							}
						}

						$displayIDs = array_merge($news->posts, $camps->posts, $events);

						$args = array('post__in' => $displayIDs, 'post_type' => array('news', 'event', 'camp'), 'orderby' => array('date' => 'DESC'));
						$loop = new WP_Query($args);
						
						while ( $loop->have_posts() ) {
							$loop->the_post();
							set_query_var('element_post', $post);
							get_template_part( 'template-parts/feed_element' );
						}
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