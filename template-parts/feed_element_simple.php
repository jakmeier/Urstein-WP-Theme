<?php
/* A template to display events, camps and news */
/* This is the simple version - without picture and with small titles*/
/* Set the $element_post for this template */
	if($element_post->post_type == 'event') {
		$meta = get_post_meta($element_post->ID);
		$place = '';
		$placeid = isset($meta['place']) ? $meta['place'][0] : false;
		if($placeid) {
			$place = esc_html(get_the_title($placeid));
		}
		$bring = isset($meta['bring']) ? esc_html($meta['bring'][0]) : '-';
		$description = isset($meta['description']) ? nl2br(esc_html($meta['description'][0])) : '-';
		$datetime = date( 'j.n. G:i', strtotime($meta['start_time'][0]) );
		$groupnames = get_groups_of_event($element_post->ID);
		
		$content =  'Gruppen: ' . implode(', ', $groupnames). 
					'<br>' . $datetime . ' Uhr, ' . $place .
					'<br>Mitnehmen: ' . $bring .
					'<br>Weitere Infos: ' . $description .
					'<p><a href="' . get_permalink($element_post->ID) .'">Zur Anmeldung</a></p>';
	} 
	elseif($element_post->post_type == 'camp') {
		$meta = get_post_meta($element_post->ID);
		$place = 'Nicht verfügbar';
		$placeid = isset($meta['place']) ? $meta['place'][0] : false;
		if($placeid) {
			$place = esc_html(get_the_title($placeid));
		}
		$description = isset($meta['description']) ? nl2br(esc_html($meta['description'][0])) : '-';
		$start_date = isset($meta['start_date']) ? date( 'j.n.', strtotime($meta['start_date'][0]) ) : '?';
		$end_date = isset($meta['end_date']) ? date( 'j.n.', strtotime($meta['end_date'][0]) ) : '?';
		$groupnames = get_groups_of_event($element_post->ID);
		
		$content =  'Gruppen: ' . implode(', ', $groupnames). 
					'<br>Datum: ' . $start_date . ' - ' . $end_date . 
					'<br>Ort: '. $place .
					'<br>Weitere Infos: ' . $description;
	} else {
		$content = get_the_content($element_post->ID);
	}
	?>
	<article class="feed-entry">
		<div class ="entry-text">
		<a href="<?php echo get_permalink($element_post->ID); ?>"><h4><?php echo $element_post->post_title;?></h4></a>
			<div class="entry-content">
				<?php echo $content;?>
				<?php edit_post_link(__('Beitrag bearbeiten','urstein'), '<p class="post-edit">', '</p>', $element_post->ID); ?>
			</div>
		</div>
	</article>

	<?php

?>