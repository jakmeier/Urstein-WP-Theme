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
					<div class="main-image">
						<div class="fb">
							<span class="dashicons dashicons-facebook"></span>
							<a href="https://www.facebook.com/pfadiherisau.ch/">Wir sind auch auf Facebook!</a>
						</div>
						<?php
							$slider_id = get_post_meta(get_the_id(), 'slider', true);
							if($slider_id) {
								echo do_shortcode("[metaslider id=" . intval($slider_id) . "]"); 
							}
							elseif ( has_post_thumbnail() ) {
								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); $thumb_url = $thumb['0'];
								the_post_thumbnail('post-image');
							} 
						?>
			    	</div> 
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
				        
        <section class="anniversary"> <!-- Jubiläum -->
          <a href="jubilaeum">
            <img src="<?php echo get_template_directory_uri() ?>/images/Logo_weiss1.png"/>
          </a>
        </section> <!-- Jubiläum -->
        
				<section> <!-- News Feed -->
					<h1> Aktuelles </h1>
					<?php
						$news_args = array('fields' => 'ids', 'post_type' => array('news'), 'orderby' => array('date' => 'DESC'), 'posts_per_page' => 10 );
						$news = new WP_Query( $news_args );
						
						$camp_args = array('fields' => 'ids', 'post_type' => array('camp'), 'orderby' => array('date' => 'DESC'), 'posts_per_page' => 4 );
						$camps = new WP_Query( $camp_args );
						
						$groups = groups_with_events();
						$events = array();
						if($groups){
							foreach($groups as $id=>$groupname){
								$group_events = get_next_x_events($id, 2);
								if($group_events) {
									foreach($group_events as $event )
										if($event->ID){
											array_push($events, $event->ID);
										}
								}
							}
						}

						$displayIDs = array_merge($news->posts, $camps->posts, $events);

						$args = array(
							'post__in' => $displayIDs, 
							'post_type' => array('news', 'event', 'camp'), 
							'orderby' => array('date' => 'DESC'),
							'date_query' => array(
								'after' => date('Y-m-d', strtotime('-60 days')))
						);
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

			    
				
				
			</div> <!-- /post-inner -->
			</div> <!-- /post-container -->

		</div> <!-- /post -->


	<?php endwhile; ?>
	<div class="clear"></div>	
</div> <!-- /content -->
<?php get_footer(); ?>