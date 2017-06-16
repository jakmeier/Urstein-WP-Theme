<?php
require_once get_template_directory() . "/functions/group_functions.php" ;

//Display additional form input
function show_related_group( $user ) { 
	$groups = all_groups();
	$selectedGroup = intval(get_the_author_meta( 'group', $user->ID ));
?>
	<h3>Gruppenzugehörigkeit</h3>
	<label>
		Gruppe
		<select name="group">
		<option value="0">Keine</option>
		<?php
			if($groups){
				foreach($groups as $id => $name){
					$selected = $selectedGroup === intval($id) ? ' selected' : '';
					echo "<option value='$id'$selected>$name</option>";
				}
			}
		?>
		</select>
	</label>
<?php 
}
add_action( 'show_user_profile', 'show_related_group' );
add_action( 'edit_user_profile', 'show_related_group' );

// Save form content
function save_group_mapping( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_usermeta( $user_id, 'group', $_POST['group'] );
}
add_action( 'personal_options_update', 'save_group_mapping' );
add_action( 'edit_user_profile_update', 'save_group_mapping' );
?>



