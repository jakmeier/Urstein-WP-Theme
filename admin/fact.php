<?php
/* Custom post type fact */
/* Used in Pfadi ABC */
/* A fact can be a explanation for a word or some other scout-related knowledge */
 
if(!function_exists('create_fact_post_type')):
	function create_fact_post_type() {
		$labels = array(
			'name'          => __('Pfadi ABC'),
			'singular_name' => __('Pfadi ABC Fakt')
		);
		$args=array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capabilities' => array(
				'edit_post'          => 'edit_facts', 
				'read_post'          => 'edit_facts', 
				'delete_post'        => 'edit_facts', 
				'edit_posts'         => 'edit_facts', 
				'edit_others_posts'  => 'edit_facts', 
				'publish_posts'      => 'edit_facts',       
				'read_private_posts' => 'edit_facts', 
				'create_posts'       => 'edit_facts', 
				'delete_posts'       => 'edit_facts', 
			),
			'hierarchical' => false,
			'supports' => array('thumbnail'),
			'menu_position' => 8,
			'menu_icon' => 'dashicons-welcome-learn-more',
			'register_meta_box_cb' => 'add_fact_post_type_metabox'
		);
		register_post_type('fact', $args);
	}
	add_action('init','create_fact_post_type');

	function add_fact_post_type_metabox(){
		add_meta_box('fact_metabox','Ort bearbeiten','fill_fact_metabox','fact','normal');
	}

	function fill_fact_metabox(){
		global $post;
		
		$title = $post->post_title;
		$content = $post->post_content;
		
		?>
		
		<div>
			<h3>Eintrag ins Pfadi ABC</h3>
			<p><label>
				Titel:<br>
				<input type="text" name="post_title" size="50" value="<?php echo $title;?>">
			</label></p>
			<p><label>
				Text<br>
				<textarea name="post_content" cols="80" rows="6" ><?php echo $content;?></textarea>
			</label></p>
		</div>
	<?php
	}
	
	// Add coordinates as columns to list view
	function fact_custom_columns($columns) {
		unset($columns['date']);
		$columns['content'] = 'Text';
		return $columns;
	}
	add_filter('manage_edit-fact_columns', 'fact_custom_columns');

	function fact_column( $colname, $cptid ) {
		if ( $colname == 'content') {
		  echo get_the_content( $cptid );
		} else {
			echo 'Nicht gefunden';	
		}
	}
	add_action('manage_fact_posts_custom_column', 'fact_column', 10, 2);
	
	// Finally, avoid showing the single view of a fact and redirect
	function redirect_single_fact() {
	  $queried_post_type = get_query_var('post_type');
	  if ( is_single() && 'fact' ==  $queried_post_type ) {
		wp_redirect( 'wiki', 301 );
		exit;
	  }
	}
	add_action( 'template_redirect', 'redirect_single_fact' );
endif;
?>