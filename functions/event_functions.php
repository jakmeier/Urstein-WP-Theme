<?php
// Get the next event in the future (if any)
function get_next_event($groupid) {
	$args = array(
		'post_type' => 'event',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => 'group' . $groupid,
				'value' => 1
			),
			array(
				'key' => 'end_time',
				'value' => date('c'),
				'compare' => '>='
			)
		),
		'order'     => 'ASC',
		'meta_key' => 'start_time',
		'orderby'   => 'meta_value',
		'meta_type' => 'DATETIME'

	);
	$event = get_posts($args);
	return $event ? $event[0] : false;
}

// Get the next event in the past (if any)
function get_previous_event($groupid) {
	$args = array(
		'post_type' => 'event',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => 'group' . $groupid,
				'value' => 1
			),
			array(
				'key' => 'end_time',
				'value' => date('c'),
				'compare' => '<'
			)
		),
		'order'     => 'DESC',
		'meta_key' => 'start_time',
		'orderby'   => 'meta_value',
		'meta_type' => 'DATETIME'

	);
	$event = get_posts($args);
	return $event ? $event[0] : false;
}

// Get the next event in the future (if any)
function get_next_x_events($groupid, $number=1) {
	$number = intval($number);
	$args = array(
		'post_type' => 'event',
		'posts_per_page' => $number,
		'meta_query' => array(
			array(
				'key' => 'group' . $groupid,
				'value' => 1
			),
			array(
				'key' => 'end_time',
				'value' => date('c'),
				'compare' => '>='
			)
		),
		'order'     => 'ASC',
		'meta_key' => 'start_time',
		'orderby'   => 'meta_value',
		'meta_type' => 'DATETIME'

	);
	$event = get_posts($args);
	return $event ? array_slice($event, 0, $number) : false;
}

// Get the next camp in the future (if any)
function get_next_camp($groupid) {
	$args = array(
		'post_type' => 'camp',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => 'group' . $groupid,
				'value' => 1
			),
			array(
				'key' => 'end_date',
				'value' => date('c'),
				'compare' => '>='
			)
		),
		'order'     => 'ASC',
		'meta_key' => 'start_date',
		'orderby'   => 'meta_value',
		'meta_type' => 'DATETIME'

	);
	$camp = get_posts($args);
	return $camp ? $camp[0] : false;
}

// Get the next camp in the past (if any)
function get_previous_camp($groupid) {
	$args = array(
		'post_type' => 'camp',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => 'group' . $groupid,
				'value' => 1
			),
			array(
				'key' => 'end_date',
				'value' => date('c'),
				'compare' => '<'
			)
		),
		'order'     => 'DESC',
		'meta_key' => 'start_date',
		'orderby'   => 'meta_value',
		'meta_type' => 'DATETIME'

	);
	$camp = get_posts($args);
	return $camp ? $camp[0] : false;
}

// Get all group ids and names that belong to an event (or camp)
function get_groups_of_event($postid){
	$groups = all_groups();
	$result = array();
	$meta = get_post_meta($postid);
	foreach($groups as $id => $name){
		$key = 'group' . $id;
		if( isset($meta[$key]) && intval($meta[$key][0]) === 1 ){
			$result[$id] = esc_html($name);
		}
	}
	return $result;
}

// Safe insert into signup DB
function add_signup_entry($postid, $value, $name, $comment) {
	/*Data validation*/	

	$postid = intval($postid);
	if ( ! $postid ) {
		return false;
	}
	// validity also checked with Foreign Key constraint

	$value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
	
	$name = sanitize_text_field($name);
	$comment = sanitize_text_field($comment);
	if ( strlen( $name ) > 64 ) {
	  $name = substr( $name, 0, 64 );
	}
	if ( strlen( $comment ) > 512 ) {
	  $comment = substr( $comment, 0, 512 );
	}
	
	global $wpdb;
	$inserted = $wpdb->insert('signup', array(
							'event' => $postid,
                            'name' => $name,
                            'comment' => $comment,
							'attends' => $value 
                            ),
							array ('%d', '%s', '%s','%d')
    );

	return $inserted > 0;
}

// Get list of attendees for an event
function get_attendees($postid){
	global $wpdb;
	$result = $wpdb->get_results("SELECT name, comment, attends FROM signup WHERE event=" . $postid . ";");
	$yes = array();
	$no = array();
	foreach($result as $row){
		if($row->attends){
			array_push($yes, array('name' => $row->name, 'comment' => $row->comment));
		} else {
			array_push($no, array('name' => $row->name, 'comment' => $row->comment));
		}
	}
	$return['yes'] = $yes;
	$return['no'] = $no;
	return $return;
}
?>