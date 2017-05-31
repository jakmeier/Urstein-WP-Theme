<?php
/* Returns a list of key-value pairs for all available groups (id=>title) */
function all_groups() {
	global $wpdb;
	$result = $wpdb->get_results("SELECT * FROM groups;");
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
	$groups = all_groups();
	/*foreach($groups as $key=>$group) {
		if( intval($group->id) >= 100) {
		   unset($groups[$key]);
		}
	}*/
	foreach($groups as $id=>$name) {
		if( intval($id) >= 100) {
		   unset($groups[$id]);
		}
	}
	return $groups;
}	

function the_group_url($groupid){
	echo get_the_group_url($groupid);
}
function get_the_group_url($groupid){
	global $wpdb;
	$result = $wpdb->get_results("SELECT page FROM groups WHERE id =" . $groupid .";");
	return get_permalink(intval($result[0]->page));
}
?>		