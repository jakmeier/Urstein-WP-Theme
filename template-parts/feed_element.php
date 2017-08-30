<?php
/* A template to display events, camps and news */
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
		$start_date = date( 'j.n.', strtotime($meta['start_date'][0]) );
		$end_date = date( 'j.n.', strtotime($meta['end_date'][0]) );
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
	<div class="entry-image">
		<?php if ( has_post_thumbnail($element_post) ){
			//$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($element_post->ID), 'thumbnail_size' ); 
			//$thumb_url = $thumb['0']; 
			echo get_the_post_thumbnail($element_post, 'thumbnail');
		} elseif($element_post->post_type == 'event') {
			echo wp_get_attachment_image( get_group_thumbnail(array_keys($groupnames)), 'thumbnail');
		}
		elseif($element_post->post_type == 'news') {
			echo wp_get_attachment_image( get_theme_mod('urstein_custom_img_news'), 'thumbnail');
		}
		elseif($element_post->post_type == 'camp') {
			echo wp_get_attachment_image( get_theme_mod('urstein_custom_img_camp'), 'thumbnail');
		}								
		?>
	</div>
	<div class ="entry-text">
	<a href="<?php echo get_permalink($element_post->ID); ?>"><h3><?php echo $element_post->post_title;?></h3></a>
		<div class="entry-content">
			<?php echo $content;?>
			<?php edit_post_link(__('Beitrag bearbeiten','urstein'), '<p class="post-edit">', '</p>', $element_post->ID); ?>
		</div>
	</div>
	</article>

	<?php

?>