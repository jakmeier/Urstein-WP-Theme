<?php
require_once ("/functions/event_functions.php"); // groups_with_events
get_header(); ?>
<div class="content section-inner">		
<link rel="stylesheet" type="text/css" href="../wp-content/themes/urstein/anschlag.css" media="screen" />
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>				
		<div <?php post_class('post single'); ?>>
			<div class="post-container">
			
			<div class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</div>
			<div class="post-inner">				    
			
				$groups = groups_with_events();
				foreach($groups as $id=>$groupname):
							$event = get_next_event($id);
							if ($event):
								$meta = get_post_meta($event->ID);
								$title = $event->post_title;
								$place = $meta['place'][0];
								$bring = $meta['bring'][0];
								$datetime = date( 'j.n. G:i', strtotime($meta['start_time'][0]) );
			 ?>
						   Nächste Aktivität: <?php echo $title; ?><br>
						   <?php echo $datetime . ' ' . $place?> <br>
						   Mitnehmen: <?php echo $bring; ?> <br>
						   >>>
					   </p>
			<?php else:?>
				<a href="<?php the_group_url($id); ?>">
				<div id="wolfsstufe" class="stufen-img">
					<div class="stufen-title"><?php echo $groupname;?></div>
					<div class="next-activity-box">
						
							Nächste Aktivität: <br>
							Noch nicht verfügbar.
						
					</div>
				</div> 
				
		
		</div> <!-- /post -->
		
	<?php endwhile; else: ?>
	
		<p><?php _e("We couldn't find any posts that matched your query. Please try again.", "urstein"); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
	
</div> <!-- /content -->
								
<?php get_footer(); ?>