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
 * Stufen that have a own image for events
 */
function stufen_with_event_image() {
	global $wpdb;
	$result = $wpdb->get_results("SELECT `id`, `title` FROM `groups` WHERE `id` = 201;");
	foreach ($result as $key=>$group){
		$groups[$group->id] = $group->title;
	}
	return $groups;
}	
/*
 * Find all groups that belong to a group
 * At the moment, "Stufen" are not completely represented in the database,
 * 0 means implicitly Biberstufe, 1 Wolfsstufe and so on
 */
function groups_by_stufe($stufe){
	if(!is_numeric($stufe)) {
		return false;
	}
	switch ($stufe){
		case 0: 
			return array(2);
		case 1: 
			return array(3,4);
		case 2: 
			return array(5);
		case 3: 
			return array(6);
	}
	return false;
}
function stufe_by_group($groupid){
	if(!is_numeric($groupid)) {
		return false;
	}
	switch ($groupid){
		case 2: 
			return 0;
		case 3:
		case 4: 
			return 1;
		case 5: 
			return 2;
		case 6: 
			return 3;
	}
	return false;
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
function get_the_group_name($groupid){
	if(!is_numeric($groupid)){
		return false;
	}
	$groupid = intval($groupid);
	global $wpdb;
	$result = $wpdb->get_results("SELECT title FROM groups WHERE id =" . $groupid .";");
	return $result[0]->title;
}
function the_group_name($groupid){
	echo get_the_group_name($groupid);
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
function the_group_color($groupid){
	echo get_the_group_color($groupid);
}
function get_the_group_color($groupid){
	if(is_numeric($groupid)){
		global $wpdb;
		$result = $wpdb->get_results("SELECT `color` FROM `groups` WHERE `id` = $groupid;");
		if(is_array($result) && isset($result[0]->color) && check_color($result[0]->color)){
			return $result[0]->color;
		}
	}
	return '#000000';
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
		if(count($groups) == 1){
			$groups = $groups[0];
		}
		// 1. Stufe
		else if(groups_by_stufe(1) == $groups) {
			global $wpdb;
			$result = $wpdb->get_results("SELECT `image` FROM `groups` WHERE `id` = 201;");
			if(is_array($result) && isset($result[0]->image) && intval($result[0]->image) > 0) {
				return $result[0]->image;
			}
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
	return true;
}

// Save update for color
function db_save_group_color($postid, $col){
	$postid = intval($postid);
	
	if(!current_user_can('edit_groups')){
		return false;
	}
	if(!check_color($col)){
		return false;
	}
	global $wpdb;
	$updated = $wpdb->update(
			'groups', 
			array( 'color' => strval($col) ),
			array('id' => $postid),  
			array ('%s'),
			array ('%d')
		);
	if($updated === false){
		return false;
	}
	return true;
}

/**
 * Function that will check if value is a valid HEX color.
 */
function check_color( $value ) { 
     
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
        return true;
    }
     
    return false;
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

// Get leaders associated with a group
function get_leaders($group_id){
	if(!is_numeric($group_id)){
		return false;
	}
	$group_id = intval($group_id);
	
	// Get users directly leading the group
	$users = get_users(
		array(
			'role__not_in' => array('division_leader'),
			'meta_key' => 'group', 
			'meta_value' => $group_id,
			'fields' => array( 'ID' )
			));
	$result = array();
	if(is_array($users)){
		foreach($users as $key => $user) {
			array_push($result, $user->ID);
		}
		return $result;
	}
	else {
		return false;
	}
}

// Stufenleiter
function get_division_leader($stufe) {
	$users = array();
	$groups = groups_by_stufe($stufe);
	if(is_array($groups)){
		foreach($groups as $group) {
			$users = array_merge(
				$users,
				get_users(
					array(
						'role' => 'division_leader',
						'meta_key' => 'group', 
						'meta_value' => $group,
						'fields' => array( 'ID' )
					)
				)
			);
		}
	}		
	$result = array();
	if(is_array($users)){
		foreach($users as $key => $user) {
			array_push($result, $user->ID);
		}
		return $result;
	}
	else {
		return false;
	}
}

/* Looks up the album associated with the group, if exists */
function get_group_album($group){
	if(is_numeric($group)){
		$group = intval($group);
	}
	if(is_int($group)){
		// Lookup attached default thumbnail for group
		global $wpdb;
		$group = intval($group);
		$result = $wpdb->get_results("SELECT `album` FROM `groups` WHERE `id` = $group;");
		if(is_array($result) && isset($result[0]->album) && intval($result[0]->album) > 0) {
			return $result[0]->album;
		}
	}
	// Default picture:
	return false;
}

/* Get list of ids and names for groups that have an associated album */
function groups_with_album(){
	global $wpdb;
	$result = $wpdb->get_results("SELECT `id`, `title` FROM `groups` WHERE `album` > 0;");
	foreach ($result as $key=>$group){
		$groups[$group->id] = $group->title;
	}
	return $groups;
}
?>