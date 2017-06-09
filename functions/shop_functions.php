<?php
// Safe insert into shop_items DB
function add_shop_item($name, $description, $position, $price, $image_path = '') {
	return update_shop_item(null, $name, $description, $position, $price, $image_path);
}
// Safe insert/update into shop_items DB
function update_shop_item($id, $name, $description, $position, $price, $image_path = '') {
	/* Make sure user is allowed to edit shop items */
	if(!current_user_can('edit_shop')){
		return 'Nicht ausreichend Rechte um Warenartikel zu ändern.';
	}
	
	/*Data validation*/	
	
	
	// ID must be int
	if(isset($id)){
		$id = intval($id);
		if($id <= 0){
			return 'nonpositive id';
		}
	}
	
	//title is s VARCHAR(64)
	$name = sanitize_text_field($name);
	if ( strlen( $name ) > 64 ) {
	  $name = substr( $name, 0, 64 );
	}

	//description is s VARCHAR(1024)	
	$description = sanitize_textarea_field($description);
	if ( strlen( $description ) > 1024 ) {
	  $description = substr( $description, 0, 1024 );
	}
	
	// position is a signed int
	$position = intval($position);
	if ( !$position &! $position === 0 ) {
		return 'broken position';
	}
	
	// price is a float
	if ( !is_numeric($price) ){
		return 'broken price';
	}
	$price = floatval($price);

	// image is a text value (a path to an attachment)
	if( !is_string($image_path) ) {
		return 'broken image';
	}

	global $wpdb;
	if(isset($id)){
		$updated = $wpdb->update(
							'shop_items', 
							array(
								'title' => $name,
								'description' => $description,
								'image' => $image_path, 
								'position' => $position,
								'price' => $price
							  ),
							array('id' => $id),  
							array ('%s', '%s','%s', '%d', '%f'),
							array ('%d')
		);
		if ($updated === false){
			return 'update error';
		}
	}
	else {
		$inserted = $wpdb->insert(
							'shop_items', 
							array(
								'title' => $name,
								'description' => $description,
								'image' => $image_path, 
								'position' => $position,
								'price' => $price
							  ),
							array ('%s', '%s','%s', '%d', '%f')
		);
		if ($inserted <= 0){
			return 'insert error';
		}
	}
	return 'ok';
}

// Get list of items sorted by position
function get_shop_items(){
	global $wpdb;
	$result = $wpdb->get_results("SELECT * FROM shop_items ORDER BY position ASC;");
	return $result;
}

// Delete shop item by id
function db_delete_shop_item($id){
	/* Make sure user is allowed to edit shop items */
	if(!current_user_can('edit_shop')){
		return false;
	}
	
	// ID must be positive int
	if(isset($id)){
		$id = intval($id);
		if($id <= 0){
			return 'non-positive id';
		}
	}
	
	global $wpdb;
	$deleted = $wpdb->delete('shop_items', array('id' => $id), array('%d'));
	if($deleted === false){
		return 'delete error 1 on delete id' . $id;
	}
	if($deleted === 0){
		return 'delete error 2 on delete id' . $id;
	}
	return 'ok';
}

?>