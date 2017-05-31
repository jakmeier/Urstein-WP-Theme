<?php
	// Called by admin menu for overview of events
	function attendees_content(){
		?>
			<div class="urstein-wrap">		
				<?php
					if (isset($_GET['eventid'])) : 
					$data = get_attendees($_GET['eventid']);
					// Show list for picked event
				?>
					<h2>An- und Abmeldungen für <?php echo get_the_title($_GET['eventid']); ?></h2>
					<h3>Angemeldete (<?php echo count($data['yes']);?>)</h3>
					<?php foreach($data['yes'] as $attendee):?>
						<ul>
							<li>
								<strong><?php echo $attendee['name'];?>: </strong>
								<?php echo $attendee['comment'];?>
							</li>
						</ul>
					<?php endforeach;?>
					<h3>Abgemeldete (<?php echo count($data['no']);?>)</h3>
					<?php foreach($data['no'] as $attendee):?>
						<ul>
							<li>
								<strong><?php echo $attendee['name'];?>: </strong>
								<?php echo $attendee['comment'];?>
							</li>
						</ul>
					<?php endforeach;?>

				<?php 
					else : 
					// Show list of upcoming events
					echo '<h2>Nächste Übungen</h2>';
					$groups = groups_with_events();
					foreach($groups as $id=>$groupname):
							$event = get_next_event($id);
							if ($event):
							$link_url = admin_url('admin.php?page=event%2Fattendees&eventid=' . $event->ID);
							$title = $event->post_title;
							$date = strtotime(get_post_meta($event->ID, 'start_time')[0]);
							$time = date('G:i', $date);
							$date = date('j.n.', $date);
				?>
					<h3><?php echo $groupname; ?></h3>
					<ul><li><a href="<?php echo $link_url ?>">
						<?php echo $title; ?> am <?php echo $date; ?> ab <?php echo $time; ?> Uhr
					</a></li></ul>
				<?php
							endif;
					endforeach;
					endif; ?>
			</div>
		<?php
	}
?>