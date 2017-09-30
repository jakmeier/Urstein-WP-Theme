<?php 
/* Modifies the foogallery */

require_once(get_template_directory() . '/functions/group_functions.php');

add_filter('foogallery_gallery_posttype_register_args', 'test_foo', 0, 1);
function test_foo($args){
	$args['register_meta_box_cb'] = 'add_gallery_group_metabox';
	return $args;
}
function add_gallery_group_metabox(){
	add_meta_box('gallery_group_metabox','Gruppenzugehörigkeit','fill_gallery_group_metabox','foogallery','normal');
}

function fill_gallery_group_metabox(){
	global $post;
	
	$groups = groups_with_album();
	
	?>
		<div class="group-checkbox">
			<ul>
				<?php
					foreach($groups as $id=>$groupname){
						$albumid = get_group_album($id);
						$album = get_post_meta($albumid, 'foogallery_album_galleries', true);
						$checked = in_array($post->ID, $album);
						echo '<li><label>';
						echo '<input type="checkbox" name="group' . $id . '"' . ($checked ? 'checked' : '') . '>';
						echo $groupname . '</label></li>';
					}
				?>
			</ul><br>
			<p><i> Bitte ankreuzen auf welcher Gruppenseite die Bilder verlinkt werden sollen.</i></p>
		</div>
	<?php
}

function gallery_save_groups($post_id){
	if(get_post_type($post_id) === 'foogallery'){
		if(!current_user_can('edit_gallery', $post_id)){
			return $post_id;
		}
		
		// Save album in ALL IMAGES if not there yet
		$all_pics_id = 542; // Hard coded, local: 553, Online 542
		$all_pics_album = get_post_meta($all_pics_id, 'foogallery_album_galleries', true);
		if(! in_array($post_id, $all_pics_album)) {
			array_push($all_pics_album, $post_id);
			update_post_meta($all_pics_id, 'foogallery_album_galleries', $all_pics_album);
		}
		
		// read each checkbox value and save each assignment
		$groups = groups_with_album();
		foreach($groups as $id=>$groupname){
			// Get the group album
			$albumid = get_group_album($id);
			$album = get_post_meta($albumid, 'foogallery_album_galleries', true);
			if(isset($_POST['group' . $id])){
				// Assign gallery to album if not in there, yet
				if(! in_array($post_id, $album)) {
					array_push($album, $post_id);
					update_post_meta($albumid, 'foogallery_album_galleries', $album);
				}
			}
			else {
				//If assigned, remove gallery from album
				if(($key = array_search($post_id, $album)) !== false) {
					unset($album[$key]);
					update_post_meta($albumid, 'foogallery_album_galleries', $album);
				}
			}
		}
	}
}
add_action('foogallery_after_save_gallery','gallery_save_groups',1,2);

?>