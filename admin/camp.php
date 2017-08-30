<?php
/* Custom post type camp
 * A camp is a one-time event like SoLa 2016.
 * It is listed in Information>Camp and shows up in the home feed
 */

require_once get_template_directory() . "/functions/group_functions.php" ;
 
if(!function_exists('create_camp_post_type')):
	function create_camp_post_type() {
		$labels = array(
			'name'          => __('Lager'),
			'singular_name' => __('Lager')
		);
		$args=array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capabilities' => array(
				'edit_post'          => 'edit_camps', 
				'read_post'          => 'edit_camps', 
				'delete_post'        => 'edit_camps', 
				'edit_posts'         => 'edit_camps', 
				'edit_others_posts'  => 'edit_camps', 
				'publish_posts'      => 'edit_camps',       
				'read_private_posts' => 'edit_camps', 
				'create_posts'       => 'edit_camps', 
			),
			'hierarchical' => false,
			'supports' => array(
				'thumbnail'
			),
			'menu_position' => 7,
			'register_meta_box_cb' => 'add_camp_post_type_metabox'
		);
		register_post_type('camp', $args);
	}
	add_action('init','create_camp_post_type');

	function add_camp_post_type_metabox(){
		add_meta_box('camp_metabox','Lager bearbeiten','fill_camp_metabox','camp','normal');
	}

	function fill_camp_metabox(){
		global $post;
		
		$current_camp = get_post_custom($post->ID);
		$title = $post->post_title;
		$start = isset($current_camp['start_date']) ? $current_camp['start_date'][0]: false;
		$end = isset($current_camp['end_date']) ? $current_camp['end_date'][0] : false;
		$description = isset($current_camp['description']) ? $current_camp['description'][0] : false;
		$place = isset($current_camp['place']) ?$current_camp['place'][0] : false;
		$x = isset($current_camp['xcoordinate']) ?$current_camp['xcoordinate'][0] : false;
		$y = isset($current_camp['ycoordinate']) ?$current_camp['ycoordinate'][0] : false;
		
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
		
		//nonce for file upload
		wp_nonce_field(plugin_basename(__FILE__), 'signup_sheet_nonce');
		wp_nonce_field(plugin_basename(__FILE__), 'last_info_sheet_nonce');
		
		?>
		
		<div>
			<p><label>Lagertitel<br><input type="text" name="post_title" size="50"
				value="<?php echo $title?$title:'';?>"></label>
			</p>
			<p> <label>Beschreibung<br><textarea rows="8" cols="60" name="description"
				><?php echo $description?></textarea></label>
			</p>
			<p> <div class="group-checkbox" >Welche Gruppen nehmen teil?<br>
				<ul>
				<?php
					foreach($groups as $id=>$groupname){
						echo '<li><label>';
						echo '<input type="checkbox" name="group' . $id . '"' . (isset($current_camp['group' . $id]) && $current_camp['group' . $id][0] ? 'checked' : '') . '>';
						echo $groupname . '</label></li>';
					}
				?>
				</ul>
			</div></p>
			<div class="clear-float"></div>
			<p><label>Anfang<br><input type="date" name="start_date" size="50"
				value="<?php echo $start;?>"></label>
			</p>
			<p> <label>Ende<br><input type="date" name="end_date" size="50"
				value="<?php echo $end;?>"></label>
			</p>
			<p><label>Ort des Lagers:<br><select name="place">
				<option value="0">Kein Ort angegeben</option>
				<?php
					if($places){
						foreach($places as $place_post){
							$id = $place_post->ID;
							$name = $place_post->post_title;
							$selected = $selectedStartPlace === intval($id) ? ' selected' : '';
							echo "<option value='$id'$selected>$name</option>";
						}
					}
				?>
			</select></p>
			<h3>Anmeldung</h3>
			<p> 
				<label>Neue Datei hochladen:<br>
					 <input type="file" name="signup_sheet"><br>	
				</label>
				<?php if(isset($current_camp['signup_sheet']) && $current_camp['signup_sheet']): ?>
				</label>
					<input type="checkbox" name="delete_signup_sheet">Lösche bestehende Datei
				</label>
				<?php endif; ?>
			</p>
			<h3>Letzte Infos</h3>
			<p> 
				<label>Neue Datei hochladen:<br>
					 <input type="file" name="last_info"><br>	
				</label>
				<?php if(isset($current_camp['last_info']) && $current_camp['last_info']): ?>
				</label>
					<input type="checkbox" name="delete_last_info">Lösche bestehende Datei
				</label>
				<?php endif; ?>
			</p>
		</div>
	<?php
	}

	function camp_post_save_meta($post_id, $post){
	if(get_post_type($post) === 'camp'){
		if(!current_user_can('edit_camps', $post->ID )){
			return $post->ID;
		}
		if(!wp_verify_nonce($_POST['signup_sheet_nonce'], plugin_basename(__FILE__))){
			return $post->ID;
		}
		if(!wp_verify_nonce($_POST['last_info_sheet_nonce'], plugin_basename(__FILE__))){
			return $post->ID;
		}
		
		$camp_post_meta['start_date'] = isset($_POST['start_date']) ? $_POST['start_date'] : '';
		$camp_post_meta['end_date'] = isset($_POST['end_date']) ? $_POST['end_date'] : '';
		$camp_post_meta['description'] = isset($_POST['description']) ? $_POST['description'] : '';
		$camp_post_meta['place'] = isset($_POST['place']) ? $_POST['place'] : '';
		
		// read each checkbox value and save each assigned group as own meta field
		$groups = groups_with_events();
		foreach($groups as $id=>$groupname){
			$camp_post_meta['group' . $id] = isset($_POST['group' . $id]);
		}
		
		// Prepare attached files and mark old files for deletion if necessary
		if(!empty($_FILES['signup_sheet']['name'])){
			$type = wp_check_filetype(basename($_FILES['signup_sheet']['name']))['type'];
			if($type === 'application/pdf'){
				$upload = wp_upload_bits($_FILES['signup_sheet']['name'], null, file_get_contents($_FILES['signup_sheet']['tmp_name']));
				if(isset($upload['error']) && $upload['error'] != 0){
					wp_die("Die Anmeldung konnte nicht hochgeladen werden");
				} else {
					$file = unserialize(get_post_meta($post->ID, 'signup_sheet', true));
					unlink($file['file']); // Note: Potentially uncaught error
					$camp_post_meta['signup_sheet'] = wp_slash($upload);
				}
			} else {
				wp_die( "Die Anmeldung konnte nicht hochgeladen werden da kein PDF.");
			}
		} elseif (isset($_POST['delete_signup_sheet'])){
			$file = get_post_meta($post->ID, 'signup_sheet', true);
			if(isset($file['file']) && unlink($file['file'])){
				$camp_post_meta['signup_sheet'] = null; 
			} else {
				var_dump($file);
				wp_die("Datei konnte nicht gelöscht werden.<br>");
			}
		}
		if(!empty($_FILES['last_info']['name'])){
			$type = wp_check_filetype(basename($_FILES['last_info']['name']))['type'];
			if($type === 'application/pdf'){
				$upload = wp_upload_bits($_FILES['last_info']['name'], null, file_get_contents($_FILES['last_info']['tmp_name']));
				if(isset($upload['error']) && $upload['error'] != 0){
					wp_die("Die letzten Infos konnten nicht hochgeladen werden");
				} else {
					$camp_post_meta['last_info'] = wp_slash($upload);
				}
			} else {
				wp_die( "Die letzen Infos konnten nicht hochgeladen werden da kein PDF.");
			}
		} elseif (isset($_POST['delete_last_info'])){
			$file = get_post_meta($post->ID, 'last_info', true);
			if(isset($file['file']) && unlink($file['file'])){
				$camp_post_meta['last_info'] = null; 
			} else {
				var_dump($file);
				wp_die("Datei konnte nicht gelöscht werden.<br>");
			}
		}
		
		// add values as custom fields
		foreach($camp_post_meta as $key => $value){
			if(get_post_meta($post->ID, $key, false)){
				// if the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			}else{
				// if the custom field doesn't have a value
				add_post_meta($post->ID, $key, $value);
			}
			if(!$value){
				// delete if blank
				delete_post_meta($post->ID, $key);
			}
		}
		
		// Remove the save_post action for the call to wp_update_post, to avoid
		// looping on it.
		remove_action('save_post', 'camp_post_save_meta', 1, 2);
		wp_update_post(array(
			'ID'         => $post_id,
			'post_title' => isset($_POST['post_title']) ? $_POST['post_title'] : 'Kein Lagertitel verfügbar'
		));
		add_action('save_post','camp_post_save_meta',1,2);
	}}
	add_action('save_post','camp_post_save_meta',1,2);
	
	// Allow file upload
	function update_camp_edit_form(){
		global $post;
		if(get_post_type($post) === 'camp'){
			echo ' enctype="multipart/form-data"';
		}
	}
	add_action('post_edit_form_tag', 'update_camp_edit_form');

  // Add start and end as columns to list of camps
	function camp_custom_columns($columns) {
		$columns['start_date'] = 'Anfang';
		$columns['end_date'] = 'Ende';
		return $columns;
	}
	add_filter('manage_edit-camp_columns', 'camp_custom_columns');
	add_filter('manage_edit-camp_sortable_columns', 'camp_custom_columns');

	function camp_column( $colname, $cptid ) {
		if ( $colname == 'start_date') {
		  echo get_post_meta( $cptid, 'start_date', true );
		} elseif ($colname == 'end_date') {
			echo get_post_meta($cptid, 'end_date', true);
		} else {
			echo 'Nicht gefunden';	
		}
	}
	add_action('manage_camp_posts_custom_column', 'camp_column', 10, 2);
	function sort_date_camp( $vars ) {
		if( array_key_exists('orderby', $vars )) {
			if('Anfang' == $vars['orderby']) {
				$vars['orderby'] = 'start_date';
				$vars['meta_key'] = 'start_date';
			} elseif ('Ende' == $vars['orderby']) {
				$vars['orderby'] = 'end_date';
				$vars['meta_key'] = 'end_date';
			}
		}
		return $vars;
	}
	add_filter('request', 'sort_date_camp');
	
	function remove_quicklink( $actions = array(), $post = null ) {
		// Remove Quick edit Link
		if ( isset( $actions['inline hide-if-no-js'] ) ) {
			unset( $actions['inline hide-if-no-js'] );
		}
	}
	add_filter( 'post_row_actions', 'remove_quicklink', 10, 2 );	

endif;
?>