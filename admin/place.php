<?php
/* Custom post type place */
 
if(!function_exists('create_place_post_type')):
	function create_place_post_type() {
		$labels = array(
			'name'          => __('Orte'),
			'singular_name' => __('Ort')
		);
		$args=array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => false,
			'exclude_from_search' => true, 
			'show_in_rest' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capabilities' => array(
				'edit_post'          => 'edit_places', 
				'read_post'          => 'edit_places', 
				'delete_post'        => 'edit_places', 
				'edit_posts'         => 'edit_places', 
				'edit_others_posts'  => 'edit_places', 
				'publish_posts'      => 'edit_places',       
				'read_private_posts' => 'edit_places', 
				'create_posts'       => 'edit_places', 
				'delete_posts'       => 'edit_places', 
			),
			'hierarchical' => false,
			'supports' => array(''),
			'menu_position' => 7,
			'menu_icon' => 'dashicons-location-alt',
			'register_meta_box_cb' => 'add_place_post_type_metabox'
		);
		register_post_type('place', $args);
	}
	add_action('init','create_place_post_type');

	function add_place_post_type_metabox(){
		add_meta_box('place_metabox','Ort bearbeiten','fill_place_metabox','place','normal');
	}

	function fill_place_metabox(){
		global $post;
		
		$title = $post->post_title;
		$mapLink = $post->post_content;
		$post_id =  $post->ID;
		$adminLink = get_post_meta($post_id, 'admin_link', true);
		$x_coordinate = get_post_meta($post_id, 'x', true);
		$y_coordinate = get_post_meta($post_id, 'y', true);
		
		?>
		
		<div>
			<h3>Ort definieren</h3>
			<p> Um einen Ort zu erstellen, gib einfach einen Anzeigenamen und die schweizer Koordinaten ein, der Ort wird dann automatisch markiert und die Karte zentriert. Die Koordinaten können beispielsweise aus <a href="https://tools.retorte.ch/map/">tools.retorte.ch/map/</a> herausgelesen werden.</p>
			<p><label>Bezeichnung Ort:<br><input type="text" name="post_title" size="50"
				value="<?php echo $title;?>"></label>
			</p>
			<p><label>Schweizer Koordinate<br>
					<input type="number" name="x" size="50" value="<?php echo $x_coordinate;?>"> |
					<input type="number" name="y" size="50" value="<?php echo $y_coordinate;?>">
				</label>
			</p>
			<h3>Auf der Krate zeichnen</h3>
			<p>
				Um die Karte mit Markierungen zu ergänzen, gehe zu <a href = "https://map.search.ch/">Search.ch</a>, erstelle eine Karte mit Zeichnungen und speichere sie unter <i>Funktionen</i>. Kopiere dann einfach den Kartenlink der angezeigt wird. Zusätzlich kannst du noch den Adminlink kopieren, um die Karte später wieder bearbeiten zu können.
			</p>
			<p><label>Search.ch Karte URL:<br><input type="text" name="map" size="50"
				value="<?php echo $mapLink;?>"></label>
			</p>
			<p><label>Search.ch Karte Admin Link:<br><input type="text" name="admin_link" size="50"
				value="<?php echo $adminLink;?>"></label>
			</p>
			
			
		</div>
	<?php
	}

	function place_post_save_meta($post_id, $post){
		if(get_post_type($post) === 'place'){
			if(!current_user_can('edit_places', $post->ID )){
				return $post->ID;
			}
			// Remove the save_post action for the call to wp_update_post, to avoid
			// looping on it.
			remove_action('save_post', 'place_post_save_meta', 1, 2);
			
			$meta_keys = array('admin_link', 'x', 'y');
			foreach ($meta_keys as $key) {
				if(isset($_POST[$key])) {
					update_post_meta($post_id, $key, $_POST[$key]);
				}
			}
			if(isset($_POST['post_title']) || isset($_POST['map'])) {
				wp_update_post(array(
					'ID'         => $post_id,
					'post_title' => $_POST['post_title'],
					'post_content' => $_POST['map']
				));
			}
			add_action('save_post','place_post_save_meta',1,2);
		}
  }
	add_action('save_post','place_post_save_meta',1,2);
	
	// Add coordinates as columns to list view
	function place_custom_columns($columns) {
		unset($columns['date']);
		$columns['x'] = 'X';
		$columns['y'] = 'Y';
		return $columns;
	}
	add_filter('manage_edit-place_columns', 'place_custom_columns');
	add_filter('manage_edit-place_sortable_columns', 'place_custom_columns');

	function place_column( $colname, $cptid ) {
		if ( $colname == 'x') {
		  echo get_post_meta( $cptid, 'x', true);
		} elseif ($colname == 'y') {
			echo get_post_meta($cptid, 'y', true);
		} else {
			echo 'Nicht gefunden';	
		}
	}
	add_action('manage_place_posts_custom_column', 'place_column', 10, 2);
	function sort_places( $vars ) {
		if (isset($vars['post_type']) && $vars['post_type'] == 'place'){ 
			if( array_key_exists('orderby', $vars )) {
				if('X' == $vars['orderby']) {
					$vars['orderby'] = 'x';
					$vars['meta_key'] = 'x';
				} elseif ('Y' == $vars['orderby']) {
					$vars['orderby'] = 'y';
					$vars['meta_key'] = 'y';
				}
			}
		}
		return $vars;
	}
	add_filter('request', 'sort_places');
endif;
?>