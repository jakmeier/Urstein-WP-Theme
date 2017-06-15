<?php
/* 
 * Setting up quickliks for home
 */

function add_home_meta()
{
    global $post;
    if(!empty($post))
    {
        if($post->post_name == 'home' )
        {
            add_meta_box(
                'home_meta', // $id
                'Direkte Links', // $title
                'display_quicklink', // $callback
                'page', // $page
                'normal', // $context
                'high'); // $priority
        }
    }
}
add_action('add_meta_boxes', 'add_home_meta');

function display_quicklink()
{
	//get meta
	global $post;
	$links = get_post_meta($post->ID, 'quicklinks', true);
	//echo var_dump($links);
	if(!$links){
		$links = array(array('1',''));
	}
	$pages = get_pages()
	?>
	<style>
		#quicklink-input {
			display: flex;
		}
	</style>
	<script>
		jQuery(document).ready(function(){
			jQuery('#add-link-btn').click(function(){
				var i = jQuery('#link-list select').size();
				var newRow = jQuery('#quicklink-input').last().clone();
				newRow.find('input[type=text]').first().val('');
				newRow.find('input[type=text]').first().attr('name', 'text' + i);
				newRow.find('select').first().attr('name', 'link' + i);
				newRow.appendTo('#link-list');
			 });
		})
	</script>
	<ol id="link-list">
		<?php
			foreach ($links as $i => $pair):
			$text = $pair[0];
			$link = intval($pair[1]);
		?>
			<div id="quicklink-input">
				<label>Anzeigen<br>
					<input type="text" name="text<?php echo $i;?>" size="50" value="<?php echo $text?>">	
				</label>
				<label>Verlinkte Seite<br>
					<select name="link<?php echo $i;?>">
						<?php
						if( $pages ){
							foreach( $pages as $page ){
								if($link === $page->ID){
									$selected = ' selected ';
								} else {
									$selected = '';
								}
								echo '<option value="' . $page->ID . '"' . $selected . '>' . $page->post_title . '</option>';
							}
						}
						?>
					</select>
				</label>
			</div>
			
		<?php endforeach; ?>
	</ol>
	<span class="button" id="add-link-btn"> Neuer Link<span/>
	<?php
}


	function save_quicklinks($post_id, $post){
		//$quicklinks = array();
		$i = 0;
		while(isset($_POST['text'.$i])) {
			if($_POST['text'.$i] != '') {
				$quicklinks[$i] = array($_POST['text'.$i], $_POST['link'.$i]);	
			}
			$i++;
		}
		
		if(!current_user_can('edit_quicklinks', $post->ID )){
			return $post->ID;
		}
		
		// add quicklink array as custom fields
		$key = 'quicklinks';
		if(get_post_meta($post->ID, $key, false)){
			// if the custom field already has a value
			update_post_meta($post->ID, $key, $quicklinks);
		}else{
			// if the custom field doesn't have a value
			add_post_meta($post->ID, $key, $quicklinks);
		}
		if(count($quicklinks) === 0){
			// delete if blank
			delete_post_meta($post->ID, $key);
		}
  }
	add_action('save_post','save_quicklinks',1,2);
?>