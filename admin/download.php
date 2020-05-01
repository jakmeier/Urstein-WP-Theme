<?php
/* Custom post type download
 * A download is a file that can publically be downloaded
 * It is listed in the Information>Downloads page
 */
 
if(!function_exists('create_download_post_type')):
	function create_download_post_type() {
		$labels = array(
			'name'          => __('Downloads'),
			'singular_name' => __('Download')
		);
		$args=array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capabilities' => array(
				'edit_post'          => 'edit_downloads', 
				'read_post'          => 'edit_downloads', 
				'delete_post'        => 'edit_downloads', 
				'edit_posts'         => 'edit_downloads', 
				'edit_others_posts'  => 'edit_downloads', 
				'publish_posts'      => 'edit_downloads',       
				'read_private_posts' => 'edit_downloads', 
				'create_posts'       => 'edit_downloads', 
				'delete_posts'       => 'edit_downloads',
			),
			'supports' => array('thumbnail' => false),
			'hierarchical' => false,
			'menu_position' => 8,
			'menu_icon' => 'dashicons-media-default',
			'register_meta_box_cb' => 'add_download_post_type_metabox'
		);
		register_post_type('download', $args);
	}
	add_action('init','create_download_post_type');

	function add_download_post_type_metabox(){
		add_meta_box('download_metabox','Downloads bearbeiten','fill_download_metabox','download','normal');
	}

	function fill_download_metabox(){
		global $post;
		
		$title = get_the_title($post);
		
		//nonce for file upload
		wp_nonce_field(plugin_basename(__FILE__), 'download_nonce');
		
		?>
		
		<div>
			<p><label>Titel<br><input type="text" name="post_title" size="50"
				value="<?php echo $title?$title:'';?>"></label>
			</p>
			<p> 
				<label>Neue Datei hochladen:<br>
					 <input type="file" name="download"><br>	
				</label>
			</p>
		
		</div>
	<?php
	}

	function download_post_save_meta($post_id, $post){
	if(get_post_type($post) === 'download'){
		if(!current_user_can('edit_downloads', $post->ID )){
			return $post->ID;
		}
		if(!empty($_FILES['download']) && !wp_verify_nonce($_POST['download_nonce'], plugin_basename(__FILE__))){
			return $post->ID;
		}
		
		// Prepare attached files and mark old files for deletion if necessary
		$download_post_meta = array();
		if(!empty($_FILES['download']['name'])){
			$type = wp_check_filetype(basename($_FILES['download']['name']))['type'];
			if($type === 'application/pdf'){
				$upload = wp_upload_bits($_FILES['download']['name'], null, file_get_contents($_FILES['download']['tmp_name']));
				if(isset($upload['error']) && $upload['error'] != 0){
					wp_die("Die Datei konnte nicht hochgeladen werden");
				} else {
					$file = unserialize(get_post_meta($post->ID, 'download', true));
					unlink($file['file']); // Note: Potentially uncaught error
					$download_post_meta['download'] = wp_slash($upload);
				}
			} else {
				wp_die( "Die Datei konnte nicht hochgeladen werden da kein PDF.");
			}
		}
		
		// add values as custom fields
		foreach($download_post_meta as $key => $value){
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
		remove_action('save_post', 'download_post_save_meta', 1, 2);
		wp_update_post(array(
			'ID'         => $post_id,
			'post_title' => isset($_POST['post_title']) ? $_POST['post_title'] : 'Kein Titel'
		));
		add_action('save_post','download_post_save_meta',1,2);
	}}
	add_action('save_post','download_post_save_meta',1,2);
	
	// Clean deletion
	function delete_download_post($post_id){

		if(get_post_type($post_id) === 'download'){
			$file = get_post_meta($post->ID, 'download', true);
			if(isset($file['file'])){
				//Note: pontentially uncaught error
				unlink($file['file']);
			}
		}
	}
	add_action('delete_post','delete_download_post',1,2);
	
	// Allow file upload
	function update_download_edit_form(){
		global $post;
		if(get_post_type($post) === 'download'){
			echo ' enctype="multipart/form-data"';
		}
	}
	add_action('post_edit_form_tag', 'update_download_edit_form');

	// Instead of showing a single page, download file directly
	function redirect_single_download() {
	  $queried_post_type = get_query_var('post_type');
	  if ( is_single() && 'download' ==  $queried_post_type ) {
		$file = get_post_meta(get_the_id(), 'download', true);
		wp_redirect( $file['url'], 301 );
		exit;
	  }
	}
	add_action( 'template_redirect', 'redirect_single_download' );
	
endif;
?>