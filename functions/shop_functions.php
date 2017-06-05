<?php
// Safe insert into shop_items DB
function add_shop_item($name, $description, $position, $price, $size_array = array(), $image_path = '') {
	/*Data validation*/	

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
		return false;
	}
	
	// price is a float
	if ( !is_numeric($price) ){
		return false;
	}
	$price = floatval($price);
	
	// sizes is a text value where as $size_array should be an array of at most 100 strings
	if( !is_array($size_array) || count($size_array) > 100 ){
		return false;
	}
	foreach($size_array as $size ){
		if( !is_string($size) ) {
			return false;
		}
	} 
	$sizes = serialize($size_array);
	
	// image is a text value (a path to an attachment)
	if( !is_string($image_path) ) {
		return false;
	}

	global $wpdb;
	$inserted = $wpdb->insert('shop_items', array(
							'title' => $name,
                            'description' => $description,
                            'sizes' => $sizes,
							'image' => $image_path, 
                            'position' => $position,
							'price' => $price
					      ),
						array ('%s', '%s', '%s','%s', '%d', '%f')
    );

	return $inserted > 0;
}

// Get list of items sorted by position
function get_shop_items(){
	global $wpdb;
	$result = $wpdb->get_results("SELECT * FROM shop_items ORDER_BY position;");
	return $result;
}
?>