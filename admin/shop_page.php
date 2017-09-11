<?php
/* 
 * Setting up responsible person for shop
 */

function add_shop_meta()
{
    global $post;
    if(!empty($post))
    {
        if($post->post_name == 'shop' )
        {
            add_meta_box(
                'shop_meta', // $id
                'Verantwortlich', // $title
                'select_responsible_person_shop', // $callback
                'page', // $page
                'normal', // $context
                'high'); // $priority
        }
    }
}
add_action('add_meta_boxes', 'add_shop_meta');

function select_responsible_person_shop()
{
	//get meta
	global $post;
	$person = intval(get_post_meta($post->ID, 'responsible_person', true));
	$users = get_users(
		array(
			'role__in'     => array('parents_council_president', 'parents_council_vice_president', 'parents_council_cashier', 'parents_council_actuary', 'parents_council_club_house', 'parents_council_auditor', 'shop_admin'),
			'orderby'      => 'nickname',
			'order'        => 'ASC',
			'fields'       => 'all',
	 )); 
	?>
	<div id="user-selection">
		<select name="responsible_person">
			<option value="0">Niemand</option>
		<?php
			foreach( $users as $user ){
				if($person === $user->ID){
					$selected = ' selected ';
				} else {
					$selected = '';
				}
				echo '<option value="' . $user->ID . '"' . $selected . '>' . $user->nickname . ' ' . $user->first_name . ' ' . $user->last_name .'</option>';
			}
		?>
		</select>
	</div>
			
	<?php
}


	function save_responsible_person_shop($post_id, $post){
		if($post->post_name == 'shop') {
			
			if(!current_user_can('edit_pages', $post->ID )){
				return $post->ID;
			}
			
			// add quicklink array as custom fields
			$key = 'responsible_person';
			$value = $_POST['responsible_person'];
			if(get_post_meta($post->ID, $key, false)){
				// if the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			}else{
				// if the custom field doesn't have a value
				add_post_meta($post->ID, $key, $value);
			}
		}
  }
	add_action('save_post','save_responsible_person_shop',1,2);
?>