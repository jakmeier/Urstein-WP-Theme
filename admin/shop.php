<?php
	require_once( get_template_directory() . '/functions/shop_functions.php'); //get_shop_items, add_shop_item
	function shop_admin_menu() {
		$title = 'Webshop verwalten';
		$capability = 'edit_shop';
		$menu = add_menu_page( $title, $title, $capability, 'adminshop', 'shop_admin_content', 'dashicons-cart', 9  );
		add_action( 'load-' . $menu, 'load_shop_admin_js' );
	}
	add_action( 'admin_menu', 'shop_admin_menu' );
	
    function load_shop_admin_js(){
        // Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
        add_action( 'admin_enqueue_scripts', 'enqueue_shop_admin_js' );
    }
    function enqueue_shop_admin_js(){
		//Core media script (used in shop admin)
		wp_enqueue_media();

		// Custom js to load used for shop admin
		wp_enqueue_script( 'shop-admin-js', get_template_directory_uri().'/admin/shop.js', array('jquery'));
		// And also the Stylesheet
		wp_enqueue_style( 'shop-admin-css', get_stylesheet_directory_uri() . '/admin/shop.css' );
	}
	

	function shop_admin_content(){
		$items = get_shop_items();
		//var_dump($items);
		?>
		
		<h1>Webshop Administration</h1>
		<form id="items-form">
			<ol class="wrap-items">
			<?php foreach ($items as $i => $item): //Note: Sadly, changes to the rows here must be applied to the new_item_row function in admin/shop.js?>
				<li>
					<label id="item-img<?php echo $i;?>">Bild<br>
					<?php if(is_string($item->image) && strlen($item->image) > 0):?>
						<input type="text" name="img<?php echo $i;?>" class="hidden" value="<?php echo $item->image;?>">
						<img class="item" src="<?php echo $item->image;?>">
					<?php else:?>
						<input type="text" name="img<?php echo $i;?>" class="hidden">
						<span class="item-icon dashicons dashicons-format-image"></span>
					<?php endif;?>
					</label>
					<input type="text" name="id<?php echo $i;?>" class="hidden" value="<?php echo $item->id;?>">
					<label>Warenbezeichnung<br><input required type="text" name="title<?php echo $i;?>" value="<?php echo $item->title;?>"></label>
					<label>Beschreibung<br><textarea rows="5" cols="50" name="description<?php echo $i;?>"><?php echo esc_html($item->description);?></textarea></label>
					<label>Preis in CHF<br><input type="number" step="0.05" min="0" name="price<?php echo $i;?>" value="<?php echo $item->price;?>"></label>
					<br><span class="button remove">Entferne Artikel</span>
					
				</li>
			<?php endforeach;?>
			</ol>
		</form>
		<span class="button" id="add-item-btn">Neuer Artikel</span>
		<span class="button" id="save-btn">Alles speichern</span>
		<!--<a class="button">Alles speichern</a>-->
		
			
		<?php
	}
	// AJAX
	function save_shop_items() {
		$i = 0;
		$ok = true;
		while(isset($_POST['title' . $i])){
			$id = isset($_POST['id' . $i]) ? $_POST['id' . $i] : null;
			$price = $_POST['price' . $i] ? $_POST['price' . $i] : 0.0;
			$err = update_shop_item(
				$id, // if null, will perform insert
				$_POST['title' . $i],
				$_POST['description' . $i],
				$i, //position
				$price,
				$_POST['img' . $i]
			);
			if($err === 'ok'){
				$i++;
			}
			else {
				echo 'Error: ' . $err;
				wp_die();
			}
		}
		echo 'ok';
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	add_action( 'wp_ajax_save_shop_items', 'save_shop_items' );
	
	function delete_shop_item() {
		if(isset($_POST['id'])){
			$err = db_delete_shop_item(intval($_POST['id']));
			if($err === 'ok'){
				echo 'ok';
			}
			else {
				echo 'Error: ' . $err;
			}
			wp_die();
		}
		else{
			echo 'Error: No id provided';
			wp_die();
		}
	}
	add_action( 'wp_ajax_delete_shop_item', 'delete_shop_item' );
?>