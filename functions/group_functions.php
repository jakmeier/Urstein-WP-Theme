<?php
function get_all_group_info(){
	global $wpdb;
	return $wpdb->get_results("SELECT * FROM `groups`;");
}
/* Returns a list of key-value pairs for all available groups (id=>title) */
function all_groups() {
	global $wpdb;
	$result = $wpdb->get_results("SELECT `id`, `title` FROM `groups`;");
	foreach ($result as $key=>$group){
		$groups[$group->id] = $group->title;
	}
	return $groups;
}
/*
 * The group IDs follow the rule that all groups that
 * can have events assigned to, use an ID smaller 100
 */
function groups_with_events() {
	global $wpdb;
	$result = $wpdb->get_results("SELECT `id`, `title` FROM `groups` WHERE `has_event` = 1;");
	foreach ($result as $key=>$group){
		$groups[$group->id] = $group->title;
	}
	return $groups;
}	

/*
 * Get a groupid by the postid it has been assigned to 
 * Useful to find the group that corresponds to the displayed page
 */
function get_group_id_by_post($postid){
	if(!is_numeric($postid)){
		return false;
	}
	$postid = intval($postid);
	global $wpdb;
	$result = $wpdb->get_results("SELECT `id` FROM `groups` WHERE `page` = $postid;");
	if($result){
		return $result[0]->id;
	}
	return false;
}

function the_group_url($groupid){
	echo get_the_group_url($groupid);
}
function get_the_group_url($groupid){
	if(!is_numeric($groupid)){
		return false;
	}
	$groupid = intval($groupid);
	global $wpdb;
	$result = $wpdb->get_results("SELECT page FROM groups WHERE id =" . $groupid .";");
	return get_permalink(intval($result[0]->page));
}
function the_group_image_url($groupid){
	echo get_the_group_image_url($groupid);
}
function get_the_group_image_url($groupid){
	return wp_get_attachment_url(get_group_thumbnail($groupid));
}
function the_group_content($groupid){
	echo nl2br(esc_html(get_the_group_content($groupid)));
}
function get_the_group_content($groupid){
	if(!is_numeric($groupid)){
		return false;
	}
	$groupid = intval($groupid);
	global $wpdb;
	$result = $wpdb->get_results(
		"SELECT `wp_posts`.`post_content` " . 
		"FROM `wp_posts`, `groups` " .
		"WHERE `wp_posts`.`id` = `groups`.`page` " .
		"AND `groups`.`id` = $groupid;"
	);
	if(is_array($result) && isset($result[0]->post_content)) {
		return $result[0]->post_content;
	}
	return false;
}
/* Looks up one fitting thumbnail for one or many groups */
function get_group_thumbnail($groups){
	//var_dump($groups);
	if(is_array($groups)){
		//TODO: Filter for special combinations (Puma + Cobra)
		if(count($groups) == 1){
			$groups = $groups[0];
		}
	}
	if(is_numeric($groups)){
		$groups = intval($groups);
	}
	if(is_int($groups)){
		// Lookup attached default thumbnail for group
		global $wpdb;
		$groups = intval($groups);
		$result = $wpdb->get_results("SELECT `image` FROM `groups` WHERE `id` = $groups;");
		if(is_array($result) && isset($result[0]->image) && intval($result[0]->image) > 0) {
			return $result[0]->image;
		}
	}
	// Default picture:
	return get_theme_mod('urstein_custom_img_event');
}

// Save update for image
function db_save_group_image($postid, $img){
	$postid = intval($postid);
	$img = intval($img);
	
	if(!current_user_can('edit_groups')){
		return false;
	}
	if($img <= 0){
		return false;
	}
	global $wpdb;
	$updated = $wpdb->update(
			'groups', 
			array( 'image' => strval($img) ),
			array('id' => $postid),  
			array ('%s'),
			array ('%d')
		);
	if($updated === false){
		return false;
	}
	echo 'test';
	return true;
}

// Save toggle for boolean has_event
function db_toggle_group_has_event($postid){
	if(!current_user_can('edit_groups')){
		return false;
	}
	$postid = intval($postid);

	global $wpdb;
	$result = $wpdb->get_results("SELECT `has_event` FROM `groups` WHERE `id` = $postid;");
	if(!$result){
		return false;
	}
	$flag = $result[0]->has_event;
	$flag = !$flag;

	$updated = $wpdb->update(
			'groups', 
			array( 'has_event' => $flag ),
			array('id' => $postid),  
			array ('%d'),
			array ('%d')
		);
	if($updated === false){
		return false;
	}
	return true;
}

// Get leaders data to display associated with a group
function get_leaders($group_id){
	if(!is_numeric($group_id)){
		return false;
	}
	$group_id = intval($group_id);
	$users = get_users(
		array(
			'meta_key' => 'group', 
			'meta_value' => $group_id,
			'fields' => array( 'ID', 'display_name', 'user_email' )
			));
	if(is_array($users)){
		foreach($users as $key => $user) {
			$nickname = get_user_meta($user->ID, 'nickname', true);
			if(is_string($nickname)){
				$users[$key]->nickname = $nickname;
			} else {
				$users[$key]->nickname = 'Nicht verfügbar';
			}
			$avatar = get_user_meta($user->ID, 'basic_user_avatar', true);
			if(is_string($nickname)){
				$users[$key]->avatar = $avatar;
			}
		}
		return $users;
	}
	else {
		return false;
	}
}
?>		