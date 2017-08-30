<?php get_header(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/event.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/camp.css" media="screen" />

<div class="content section-inner">						        
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	
		<div id="post-<?php the_ID(); ?>" <?php post_class('single single-post'); ?>>
			
			<div class="post-container">
	
				<div class="post-header">
					<h1 class="post-title"><?php the_title(); ?></h1>
				</div>
				
				<div class="post-inner">
				    <div class="post-content">			
						<div class="featured-media">		
							<?php the_post_thumbnail('medium'); ?>					
						</div> <!-- /featured-media -->						
						
				    	<?php 
							$meta = get_post_meta(get_the_ID()); /*print_r($meta);*/
							$start = strtotime($meta['start_date']['0']);
							$end = strtotime($meta['end_date']['0']);
							if (isset($meta['place'], $meta['place']['0'])) {
								$mapid = $meta['place']['0'];
							}
						?>
						<div class="info-table">
							<div class="info-tr">
								<div class="info-label"> Datum: </div>
								<div class="info"> 
									<?php echo date('j.n.y', $start); ?>
									-
									<?php echo date('j.n.y', $end); ?>
								</div>
							</div>
							<?php if (isset($meta['description']) && $meta['description']['0']): ?>
							<div class="info-tr separate">
								<div class="info-label"> Beschreibung: </div>
							</div>
							<div class="info-tr">
								<div class="info-long"> <?php echo nl2br(esc_html($meta['description']['0'])); ?> </div>
							</div>
							<?php endif; ?>
							<?php if (isset($meta['signup_sheet']) && $meta['signup_sheet']['0']):
									$file = unserialize($meta['signup_sheet']['0']);
							?>
							<div class="info-tr separate">
								<div class="info-label"> Anmeldeformular: </div>
								<div class="info">
									<?php
										set_query_var( 'file', $file );
										get_template_part( 'template-parts/download_link' );
									?>
								</div>
							</div>
							<?php endif; ?>
							<?php if (isset($meta['last_info']) && $meta['last_info']['0']): 
								$file = unserialize($meta['last_info']['0']);
							?>
							<div class="info-tr separate">
								<div class="info-label"> Letze Infos: </div>
								<div class="info download">
									<?php
										set_query_var( 'file', $file );
										get_template_part( 'template-parts/download_link' );
									?>
								</div>
							</div>
							<?php endif; ?>
							<div class="info-tr">
								<div class="info-label"> Lagerort: </div>
								<div class="info"> <?php echo isset($mapid) ? esc_html(get_the_title($mapid)) : ''; ?> </div>
							</div>
							<?php if(isset($mapid)): ?>
							<div class="info-tr">
								<?php
									set_query_var( 'mapid', $mapid );
									get_template_part( 'template-parts/map' );
								?>
							</div>
							<?php endif;?>
											</div> <!-- /info-table -->				
										</div> <!-- /post-content -->
										<div class="clear"></div>
				    
					<div class="post-meta">
					<?php if (current_user_can( 'edit_post', $post->ID )): ?>
						<p class="post-edit">
						 <a class="post-edit-link" href="<?php echo get_edit_post_link();?>"> Bearbeite Lager <a>
						</p>
					<?php endif;?>
					</div> <!-- /post-meta -->
			
				</div> <!-- /post-inner -->
			</div> <!-- /post-container -->
			
		</div> <!-- /post -->
		
	</div> <!-- /content -->
		
   	<?php endwhile; else: ?>

		<p><?php _e("Das Lager konnte nicht gefunden werden.", "urstein"); ?></p>
	
	<?php endif; ?>    

</div> <!-- /content -->
		
<?php get_footer(); ?>