<?php
require_once (get_template_directory() . "/functions/event_functions.php"); // groups_with_events
get_header(); ?>
<div class="content section-inner">		
<link rel="stylesheet" type="text/css" href="../wp-content/themes/urstein/anschlag.css" media="screen" />
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">				    			<div class="stufen-table">
						 <?php
				$groups = groups_with_events();
				if($groups):
				foreach($groups as $id=>$groupname):
							$event = get_next_event($id);
							if ($event):
								$meta = get_post_meta($event->ID);
								$title = esc_html($event->post_title);
								$place = esc_html($meta['place'][0]);
								$bring = esc_html($meta['bring'][0]);
								$datetime = date( 'j.n. G:i', strtotime($meta['start_time'][0]) );
			 ?>												<div class="stufen-cell">
					<div class="the-image">
					<img src="<?php the_group_image_url($id);?>">
						<div class="on-image">							<h3><a href="<?php echo get_permalink($event->ID); ?>"><?php echo $groupname;?></a></h3>							<div class="next-activity-box">							   <p> 
								   Nächste Aktivität: <?php echo $title; ?><br>
								   <?php echo $datetime . ' ' . $place?> <br>
								   Mitnehmen: <?php echo $bring; ?> <br>
								   <a href="<?php echo get_permalink($event->ID); ?>">>>> Zur Anmeldung</a>
							   </p>
							</div> <!--/next-activity-box-->						</div> <!--/on-image-->
					</div> <!--/the-image-->				</div> <!--/stufen cell-->				
						<?php else:?>
				<div class="stufen-cell">
					<div class="the-image">
					<img src="<?php the_group_image_url($id);?>">
						<div class="on-image">
							<h3><a href="<?php the_group_url($id); ?>"><?php echo $groupname;?></a></h3>
							<div class="next-activity-box">
							   <p> 
									Nächste Aktivität: <br>
									Noch nicht verfügbar.
							   </p>
							</div> <!--/next-activity-box-->
						</div> <!--/on-image-->
					</div> <!--/the-image-->
				</div> <!--/stufen cell-->
									<?php endif; endforeach; endif;?>
							</div>			</div> <!-- /post-inner -->			</div> <!-- /post-container -->
		
		</div> <!-- /post -->
		
	<?php endwhile; else: ?>
	
		<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "urstein"); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
	
</div> <!-- /content -->
								
<?php get_footer(); ?>