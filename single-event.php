<?php get_header(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/event.css" media="screen" />

<div class="content section-inner">						        
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	
	<?php    
		// Process from if necessary (In the LOOP so we can access the ID)
		require_once (get_template_directory() . "/functions/event_functions.php");
		if(isset($_POST['signup_yes'])) {
			add_signup_entry(get_the_ID(), true, $_POST['name'], $_POST['comment']);
			$confirmation = "Danke, deine Anmeldung wurde erfolgreich gespeichert.";
		}
		elseif( isset($_POST['signup_no']) ){ 
		  add_signup_entry(get_the_ID(), false, $_POST['name'], $_POST['comment']);
		  $confirmation = "Danke, deine Abmeldung wurde erfolgreich gespeichert.";
		}    
	?>
	
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
							$start_time = strtotime($meta['start_time']['0']);
							$end_time = strtotime($meta['end_time']['0']);
							if (isset($meta['place'], $meta['place']['0'])) {
								$mapid = $meta['place']['0'];
							}
							if (isset($meta['finish_place'], $meta['finish_place']['0'])) {
								$endmapid = $meta['finish_place']['0'];
							}
						?>
						<div class="info-table">
							<div class="info-tr">
								<div class="info-label"> Datum: </div>
								<div class="info"> 
									<?php echo date('j.n.y', $start_time);  
										if (date('j.n.Y', $start_time) != date('j.n.Y', $end_time)): 
									?>
									-
									<?php echo date('j.n.Y', $end_time);  
										endif; ?>
								</div>
							</div>
							<div class="info-tr">
								<div class="info-label"> Zeit: </div>
								<div class="info"> 
									<?php echo date('G:i', $start_time);  ?> 
									-
									<?php echo date('G:i', $end_time);  ?>
								</div>
							</div>
							<div class="info-tr">
								<div class="info-label"> Mitnehmen: </div>
								<div class="info"> <?php echo esc_html($meta['bring']['0']); ?> </div>
							</div>
							<div class="info-tr">
								<div class="info-label"> Treffpunkt: </div>
								<div class="info"> <?php echo esc_html(get_the_title($mapid)); ?> </div>
							</div>
							<?php if(isset($mapid)): ?>
							<div class="info-tr">
								<!--<div class="info-label"> Karte: </div> -->
								<?php
									set_query_var( 'mapid', $mapid );
									get_template_part( 'template-parts/map' );
								?>
							</div>
							<?php endif; if (isset($endmapid)): ?>
							<div class="info-tr">
								<div class="info-label"> Abtreten: </div>
								<div class="info"> <?php echo esc_html(get_the_title($endmapid)); ?> (<b>ACHTUNG:</b> Anderer Ort als Treffpunkt)</div>
							</div>
							<div class="info-tr">
								<!-- <div class="info-label"> Karte: </div> -->
								<?php
									set_query_var( 'mapid', $endmapid );
									get_template_part( 'template-parts/map' );
								?>
							</div>
							<?php endif; ?>
							
							
							<?php if (isset($meta['description']) && $meta['description']['0']): ?>
							<div class="info-tr separate">
								<div class="info-label"> Bemerkung der Leiter: </div>
							</div>
							<div class="info-tr">
								<div class="info-long"> <?php echo nl2br(esc_html($meta['description']['0'])); ?> </div>
							</div>
							<?php endif; ?>
						</div> <!-- /info-table -->	
						
						<!-- Signup form or confirmation message -->
						<?php if (isset($confirmation)) :?>
							<div class="info-tr separate">
								<div class="info-label"> <?php echo esc_html($confirmation); ?> </div>
							</div>
						<?php else: ?>
							<form method="post" action=""><div class="signup separate">
								<!--<div class="info-tr">
									<div class="info-label"> An- oder Abmeldung: </div>
								</div>-->
								<div class="info-tr">
									<!--<label> Name <input type="text" name="Name"> </label>-->
									<div class="info-label"> Name:</div>
									<input type="text" name="name" maxlength="64" autocomplete="on">
								</div>
								<div class="info-tr">
									<div class="info-label"> Kommentar:</div>
									<textarea name="comment" autocomplete="off" rows="4" maxlength="512"></textarea>
								</div>
								<div class="info-tr">
									<input type="submit" name="signup_yes" value="Anmelden">
									<input type="submit" name="signup_no" value="Abmelden">
								</div>	
							</div></form> <!-- /signup-form-->	
						<?php endif; ?>						
						
					
				    </div> <!-- /post-content -->
				    <div class="clear"></div>
				    
					<div class="post-meta">
					<?php if (current_user_can( 'edit_post', $post->ID )): ?>
						<p class="post-edit">
						 <a class="post-edit-link" href="<?php echo get_edit_post_link();?>"> Bearbeite Anlass <a>
						 <a class="post-edit-link" href="<?php echo admin_url('admin.php?page=event%2Fattendees&eventid=' . get_the_ID());?>"> Wer kommt? <a>
						</p>
					<?php endif;?>
					</div> <!-- /post-meta -->
			
				</div> <!-- /post-inner -->
			</div> <!-- /post-container -->
			
		</div> <!-- /post -->
		
	</div> <!-- /content -->
		
   	<?php endwhile; else: ?>

		<p><?php _e("Der Anlass konnte nicht gefunden werden.", "urstein"); ?></p>
	
	<?php endif; ?>    

</div> <!-- /content -->
		
<?php get_footer(); ?>