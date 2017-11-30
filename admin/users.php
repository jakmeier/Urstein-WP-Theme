<?php
require_once get_template_directory() . "/functions/group_functions.php" ;

//Display additional form input
function additional_user_fields( $user ) { 
	$groups = all_groups();
	$selectedGroup = intval(get_the_author_meta( 'group', $user->ID ));
	$tel = esc_html(get_the_author_meta( 'tel', $user->ID ));
	$address = esc_html(get_the_author_meta( 'address', $user->ID ));
	$address2 = esc_html(get_the_author_meta( 'address2', $user->ID ));
	$place = esc_html(get_the_author_meta( 'place', $user->ID ));
?>
	<h2>Optionale Angaben</h2>
	<i>Nur ausfüllen was öffentlich auf der Webseite gezeigt werden soll.</i><br>
	<table class="form-table"><tbody>
		<tr>
			<th><label for="tel"> Telefon	</label></th>
			<td><input type="tel" name="tel" id="tel" value="<?php echo $tel; ?>"></td>
		</tr>
		<tr>
			<th><label for="address">Adresse</label></th>
			<td><input type="text" name="address" id="address" value="<?php echo $address; ?>"></td>
		</tr>
		<tr>
			<th><label for="address2">Adresszeile 2</label></th>
			<td><input type="text" name="address2" id="address2" value="<?php echo $address2; ?>"></td>
		</tr>
		<tr>
			<th><label for="place">PLZ / Ort</label></th>
			<td><input type="text" name="place" id="place" value="<?php echo $place; ?>"></td>
		</tr>
	</tbody></table><!-- /form-table -->
	
	<table class="form-table"><tbody>
		<h2>Gruppenzugehörigkeit</h2>
		<tr>
			<th><label for="group">Gruppe</label></th>
			<td>
				<select id="group" name="group">
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
			</td>
		</tr>
	</tbody></table><!-- /form-table -->
<?php 
}
add_action( 'show_user_profile', 'additional_user_fields' );
add_action( 'edit_user_profile', 'additional_user_fields' );
add_action( 'user_new_form', 'additional_user_fields' );

// Save form content
function save_group_mapping( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_usermeta( $user_id, 'group', $_POST['group'] );
	update_usermeta( $user_id, 'tel', $_POST['tel'] );
	update_usermeta( $user_id, 'address', $_POST['address'] );
	update_usermeta( $user_id, 'address2', $_POST['address2'] );
	update_usermeta( $user_id, 'place', $_POST['place'] );
}
add_action( 'personal_options_update', 'save_group_mapping' );
add_action( 'edit_user_profile_update', 'save_group_mapping' );

// Display group in list view
function new_modify_user_table( $column ) {
    $column['group'] = 'Gruppe';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'group' :
            return get_the_group_name(get_the_author_meta( 'group', $user_id ));
            break;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

?>