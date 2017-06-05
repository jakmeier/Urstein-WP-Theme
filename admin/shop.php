<?php
	require_once('/../functions/shop_functions.php'); //get_shop_items, add_shop_item
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
		//TODO display shop items (Combine nicely with JS template for new rows!)
		?>
		
		<h1>Webshop Administration</h1>
		<ol class="wrap-items"></ol>
		<span class="button" id="add-item-btn">Neuer Artikel</span>
		<span class="button" id="save-btn">Alles speichern</span>
		<!--<a class="button">Alles speichern</a>-->
		
			
		<?php
	}
?>