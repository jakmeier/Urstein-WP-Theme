<?php
/* Custom post type event
 * Storing all fields as meta values
 * I suppose this is not the best performing solution,
 * a separate table for events would probably be better.
 * However, with the small number of events this is not a 
 * big issue and therfore I decided to go with the 
 * Wordpress-way of doing things. This avoids code duplication,
 * and should generally be easier to maintain.
 */

require_once get_template_directory() . "/functions/group_functions.php";
require_once get_template_directory() . "/functions/event_functions.php"; // get_attendees (inside attendees.php)
require_once get_template_directory() . "/admin/attendees.php"; // attendees_content

if (!function_exists('create_event_post_type')):
	function create_event_post_type()
	{
		$labels = array(
			'name'          => __('Übungen'),
			'singular_name' => __('Übung')
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_in_rest' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capabilities' => array(
				'edit_post'          => 'edit_events',
				'read_post'          => 'edit_events',
				'delete_post'        => 'edit_events',
				'edit_posts'         => 'edit_events',
				'edit_others_posts'  => 'edit_events',
				'publish_posts'      => 'edit_events',
				'read_private_posts' => 'edit_events',
				'create_posts'       => 'edit_events',
				'delete_posts'       => 'edit_events',
			),
			'hierarchical' => false,
			'supports' => array(
				'thumbnail',
				'custom-fields'
			),
			'menu_position' => 7,
			'register_meta_box_cb' => 'add_event_post_type_metabox'
		);
		register_post_type('event', $args);
	}
	add_action('init', 'create_event_post_type');

	function add_event_post_type_metabox()
	{
		add_meta_box('event_metabox', 'Übung bearbeiten', 'fill_event_metabox', 'event', 'normal');
	}

	function fill_event_metabox()
	{
		global $post;

		$current_event = get_post_custom($post->ID);
		$title = $post->post_title;
		$start = isset($current_event['start_time']) ? $current_event['start_time'][0] : false;
		$end = isset($current_event['end_time']) ? $current_event['end_time'][0] : false;
		$description = isset($current_event['description']) ? $current_event['description'][0] : false;
		$selectedStartPlace = isset($current_event['place']) ? intval($current_event['place'][0]) : false;

		if (isset($current_event['finish_place'])) {
			$selectedEndPlace = intval($current_event['finish_place'][0]);
		}
		$bring = isset($current_event['bring']) ? $current_event['bring'][0] : false;

		$groups = groups_with_events();
		//print_r($groups); 

		$places = get_posts(
			array(
				'posts_per_page'	=> -1,
				'orderby'	 		=> 'date',
				'order'				=> 'DESC',
				'post_type'			=> 'place'
			)
		);
		//print_r($places);

?>

		<div>
			<p><label>Was?<br><input type="text" name="post_title" size="50"
						value="<?php echo $title ? $title : 'Übung'; ?>"></label>
			</p>
			<p>
			<div class="group-checkbox">Wer?<br>
				<ul>
					<?php
					//foreach($groups as $id => $groupname){
					foreach ($groups as $id => $groupname) {
						echo '<li><label>';
						echo '<input type="checkbox" name="group' . $id . '"' . (isset($current_event['group' . $id]) && $current_event['group' . $id][0] ? 'checked' : '') . '>';
						echo $groupname . '</label></li>';
					}
					?>
				</ul>
			</div>
			</p>
			<div class="clear-float"></div>
			<p><label>Anfang<br><input type="datetime-local" name="start_time" size="50"
						value="<?php echo $start; ?>"></label>
			</p>
			<p> <label>Ende<br><input type="datetime-local" name="end_time" size="50"
						value="<?php echo $end; ?>"></label>
			</p>
			<p><label>Besammlung:<br><select name="place">
						<option value="0">Kein Ort angegeben</option>
						<?php
						if ($places) {
							foreach ($places as $place_post) {
								$id = $place_post->ID;
								$name = $place_post->post_title;
								$selected = $selectedStartPlace === intval($id) ? ' selected' : '';
								echo "<option value='$id'$selected>$name</option>";
							}
						}
						?>
					</select></p>
			<p><label>Abtreten:<br><select name="finish_place">
						<option value="0">Gleicher Ort wie Antreten</option>
						<?php
						if ($places) {
							foreach ($places as $place_post) {
								$id = $place_post->ID;
								$name = $place_post->post_title;
								$selected = $selectedEndPlace === intval($id) ? ' selected' : '';
								echo "<option value='$id'$selected>$name</option>";
							}
						}
						?>
					</select></p>
			<p><label>Mitnehmen:<br><input type="text" name="bring" size="50"
						value="<?php echo $bring; ?>"></label>
			</p>
			<p> <label>Beschreibung<br><textarea rows="8" cols="60" name="description"><?php echo $description ?></textarea></label>
			</p>
		</div>
<?php
	}

	function event_post_save_meta($post_id, $post)
	{
		if (get_post_type($post) === 'event') {
			if (!current_user_can('edit_events', $post->ID)) {
				return $post->ID;
			}

			$event_post_meta['start_time'] = isset($_POST['start_time']) ? $_POST['start_time'] : false;
			$event_post_meta['end_time'] = isset($_POST['end_time']) ? $_POST['end_time'] : false;
			$event_post_meta['description'] = isset($_POST['description']) ? $_POST['description'] : false;
			$event_post_meta['place'] = isset($_POST['place']) ? $_POST['place'] : false;
			$event_post_meta['finish_place'] = isset($_POST['finish_place']) ? $_POST['finish_place'] : false;
			$event_post_meta['bring'] = isset($_POST['bring']) ? $_POST['bring'] : false;

			// read each checkbox value and save each assigned group as own meta field
			$groups = groups_with_events();
			foreach ($groups as $id => $groupname) {
				$event_post_meta['group' . $id] = isset($_POST['group' . $id]);
			}

			// add values as custom fields
			foreach ($event_post_meta as $key => $value) {
				if (get_post_meta($post->ID, $key, false)) {
					// if the custom field already has a value
					update_post_meta($post->ID, $key, $value);
				} else {
					// if the custom field doesn't have a value
					add_post_meta($post->ID, $key, $value);
				}
				if (!$value) {
					// delete if blank
					delete_post_meta($post->ID, $key);
				}
			}
			// Remove the save_post action for the call to wp_update_post, to avoid
			// looping on it.
			if (isset($_POST['post_title'])) {
				remove_action('save_post', 'event_post_save_meta', 1, 2);
				wp_update_post(array(
					'ID'         => $post_id,
					'post_title' => $_POST['post_title']
				));
				add_action('save_post', 'event_post_save_meta', 1, 2);
			}
		}
	}
	add_action('save_post', 'event_post_save_meta', 1, 2);

	// Add start and end as columns to list of events
	function event_custom_columns($columns)
	{
		unset($columns['date']);
		$columns['place'] = 'Ort';
		$columns['start_time'] = 'Anfang';
		$columns['end_time'] = 'Ende';
		return $columns;
	}
	add_filter('manage_edit-event_columns', 'event_custom_columns');
	add_filter('manage_edit-event_sortable_columns', 'event_custom_columns');

	function event_column($colname, $cptid)
	{
		if ($colname == 'start_time') {
			echo get_post_meta($cptid, 'start_time', true);
		} elseif ($colname == 'end_time') {
			echo get_post_meta($cptid, 'end_time', true);
		} elseif ($colname == 'place') {
			echo get_the_title(get_post_meta($cptid, 'place', true));
		} else {
			echo 'Nicht gefunden';
		}
	}
	add_action('manage_event_posts_custom_column', 'event_column', 10, 2);
	function sort_events($vars)
	{
		if (isset($vars['post_type']) && $vars['post_type'] == 'event') {
			if (array_key_exists('orderby', $vars)) {
				if ('Anfang' == $vars['orderby']) {
					$vars['orderby'] = 'start_time';
					$vars['meta_key'] = 'start_time';
				} elseif ('Ende' == $vars['orderby']) {
					$vars['orderby'] = 'end_time';
					$vars['meta_key'] = 'end_time';
				} elseif ('Ort' == $vars['orderby']) {
					$vars['orderby'] = 'place';
					$vars['meta_key'] = 'place';
				}
			}
		}
		return $vars;
	}
	add_filter('request', 'sort_events');

	function attendees_link($actions = array(), $post = null)
	{
		if ($post->post_type == 'event') {
			// Add link to attendees
			$actions['attendees'] = '<a href=\'' . admin_url('admin.php?page=event%2Fattendees&eventid=' . $post->ID) . '\'>Wer kommt?</a>';
		}
		return $actions;
	}
	add_filter('post_row_actions', 'attendees_link', 10, 2);


	/* Add the page to show a list of attendees for a specific event set
	 * in the $_GET global, or, if unspecified, show a page that lists 
	 * the upcoming events whose attendees can then be viewed
	 */
	function attendees_admin_menu()
	{
		$title = 'Anmeldungen Übungen';
		add_menu_page($title, $title, 'read_attendees', 'event/attendees', 'attendees_content', 'dashicons-groups', 8);
	}
	add_action('admin_menu', 'attendees_admin_menu');

	function register_event_meta()
	{
		register_post_meta('event', 'start_time', [
			'type'         => 'string',
			'description'  => 'Start time of the event',
			'single'       => true,
			'show_in_rest' => true,
		]);

		register_post_meta('event', 'end_time', [
			'type'         => 'string',
			'description'  => 'End time of the event',
			'single'       => true,
			'show_in_rest' => true,
		]);

		register_post_meta('event', 'description', [
			'type'         => 'string',
			'description'  => 'Description of the event',
			'single'       => true,
			'show_in_rest' => true,
		]);

		register_post_meta('event', 'place', [
			'type'         => 'integer', // post ID
			'description'  => 'Where the event will start',
			'single'       => true,
			'show_in_rest' => true,
		]);

		register_post_meta('event', 'finish_place', [
			'type'         => 'integer', // optional post ID
			'description'  => 'Finish place of the event, if any',
			'single'       => true,
			'show_in_rest' => true,
		]);

		register_post_meta('event', 'bring', [
			'type'         => 'string',
			'description'  => 'What to bring for the event',
			'single'       => true,
			'show_in_rest' => true,
		]);
	}
	add_action('init', 'register_event_meta');

endif;
?>