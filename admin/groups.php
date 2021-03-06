<?php
	require_once get_template_directory() . "/functions/group_functions.php" ;

	function groups_admin_menu() {
		$title = 'Gruppen verwalten';
		$menu = add_menu_page( $title, $title, 'edit_groups', 'groups', 'groups_content', 'dashicons-networking', 8  );
		add_action( 'load-' . $menu, 'load_group_admin_js' );
	}
	add_action( 'admin_menu', 'groups_admin_menu' );

	function load_group_admin_js(){
            add_action( 'admin_enqueue_scripts', 'enqueue_group_admin_js' );
    }
    function enqueue_group_admin_js(){
		wp_enqueue_media();
		wp_enqueue_script( 'group-admin-js', get_template_directory_uri().'/admin/groups.js', array('jquery', 'wp-color-picker'));
		wp_enqueue_style( 'group-admin-css', get_stylesheet_directory_uri() . '/admin/groups.css' );
        wp_enqueue_style( 'wp-color-picker' ); 
	}
	
	function groups_content(){
		//var_dump($_POST);
		// Handle checkboxes to save
		if(isset($_POST['toggle'])){
			$toggled = db_toggle_group_has_event($_POST['toggle']);
			if(!$toggled){
				echo "<p>Ein Fehler beim Speichern ist aufgetreten.</p>";
			}
		}
		// Handle images and colors to save
		$gwe = groups_with_events();
		$stufen = stufen_with_event_image();
		foreach($gwe + $stufen as $id => $title) {
			if(isset($_POST['group' . $id]) && intval($_POST['group' . $id]) > 0){
				$saved = db_save_group_image($id, intval($_POST['group' . $id]));
				if(!$saved){
					echo "<p>Ein Fehler beim Speichern ist aufgetreten.</p>";
				}
				
			}
			if(isset($_POST['groupcol' . $id]) && $_POST['groupcol' . $id] != ''){
				$saved = db_save_group_color($id, $_POST['groupcol' . $id]);
				if(!$saved){
					echo "<p>Ein Fehler beim Speichern ist aufgetreten.</p>";
				}
			}	
		}
		
		?>
		<h1>Gruppen verwalten</h1>
		<h2>Anschlag anzeigen </h2>
		<ul id="has-events-list">
		<?php 
			$groupinfo = get_all_group_info();
			foreach($groupinfo as $group):
				if($group->id < 100):
		?>
				<li>
					<label>
						<input type="checkbox" 
							<?php echo $group->has_event ? 'checked' : ''; ?>
							onclick="toggle_has_event(<?php echo $group->id; ?>)"
						>
						<?php echo $group->title; ?>
					</label>
				</li>
		<?php endif; endforeach; ?>
		</ul>	
		<form id="toggle-form" method="post">
			<input type="text" name="toggle" class="hidden">
		</form>
		
		
		<h2> Gruppenbilder und Farben</h2>
		<form id="group-pic-form" method="post">
		<button class="save-col-btn">Farben speichern</button>
		<ul id="group-pics-list">
		<?php 
			foreach($groupinfo as $group):
				if($group->has_event):
		?>
				<li>
					<label class="group-pic-label"> <?php echo $group->title; ?><br>
					<div>
					<input type="text" name="groupcol<?php echo $group->id;?>" class="color-field"
						value="<?php echo $group->color;?>"
					></input>
					</div>
					<input type="text" name="group<?php echo $group->id;?>" class="hidden">
					<?php if(is_numeric($group->image) && intval($group->image) > 0):?>
						<img src="<?php echo wp_get_attachment_url($group->image);?>">
					<?php else:?>
						<span class="dashicons dashicons-format-image"></span>
					<?php endif;?></input>
					</label>
					
				</li>
		<?php endif; endforeach; 
			foreach($groupinfo as $group):
				if($group->id >= 200):
				?>
				<li>
					<label class="group-pic-label"> <?php echo $group->title; ?><br>
					<input type="text" name="group<?php echo $group->id;?>" class="hidden">
					<?php if(is_numeric($group->image) && intval($group->image) > 0):?>
						<img src="<?php echo wp_get_attachment_url($group->image);?>">
					<?php else:?>
						<span class="dashicons dashicons-format-image"></span>
					<?php endif;?></input>
						
					</label>
					
				</li>
		<?php endif; endforeach; ?>
		</ul>	
		</form>
		<?php
	}
?>