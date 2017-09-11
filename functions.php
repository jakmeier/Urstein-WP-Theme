<?php// Load custom roles system whenever ?reload_caps=1require_once(get_template_directory() . '/admin/roles.php');// Remove some standard admin menusfunction remove_posts_menu() {    remove_menu_page('edit.php');	remove_menu_page('edit-comments.php');	if(!current_user_can('administrator')){		  remove_menu_page('index.php');//Dashboard		  remove_menu_page('tools.php');	}}add_action('admin_init', 'remove_posts_menu');// Setting up custom post typesfunction change_commands( $actions = array(), $post = null ) {	// Remove Quick edit Link	if ( isset( $actions['inline hide-if-no-js'] ) ) {		unset( $actions['inline hide-if-no-js'] );	}	return $actions;}add_filter( 'post_row_actions', 'change_commands', 10, 2 );	
require_once(get_template_directory() . '/admin/event.php');require_once(get_template_directory() . '/admin/camp.php');require_once(get_template_directory() . '/admin/news.php');require_once(get_template_directory() . '/admin/place.php');require_once(get_template_directory() . '/admin/download.php');require_once(get_template_directory() . '/admin/fact.php');// Adjust custom post types which are registered from pluginsfunction change_post_types_of_plugins( $args, $post_type ){	// Adjust capabilities of albums and galleries	if ( 'foogallery' === $post_type || 'foogallery-album' === $post_type ) {	$args['map_meta_cap'] = null;	$args['capabilities'] = array(				'edit_post'          => 'edit_gallery', 				'read_post'          => 'edit_gallery', 				'delete_post'        => 'edit_gallery', 				'edit_posts'         => 'edit_gallery', 				'edit_others_posts'  => 'edit_gallery', 				'publish_posts'      => 'edit_gallery',       				'read_private_posts' => 'edit_gallery', 				'create_posts'       => 'edit_gallery', 				'delete_posts'       => 'edit_gallery', 			);	}	// Remove visibilty from calendars	if ( 'calendar' === $post_type) {		$args['exclude_from_search'] = true;	}	return $args;}add_filter( 'register_post_type_args', 'change_post_types_of_plugins' , 10, 2 );/* Changinge admin view for some pages to display quicklinks and responsibilities */require_once(get_template_directory() . '/admin/home.php');require_once(get_template_directory() . '/admin/pfadiheim.php');require_once(get_template_directory() . '/admin/shop_page.php');/* Loading Webshop Admin view */require_once(get_template_directory() . '/admin/shop.php');require_once(get_template_directory() . '/admin/groups.php');/* Modify user's personal information to have a mapping to groups */require_once(get_template_directory() . '/admin/users.php');
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );
// Theme setup
add_action( 'after_setup_theme', 'urstein_setup' );
function urstein_setup() {
	// Automatic feed
	//add_theme_support( 'automatic-feed-links' );
	// Set content-width
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 600;

	// Post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'post-image', 1240, 9999 );
	add_image_size( 'post-thumb', 508, 9999 );
	// Title tag
	add_theme_support( 'title-tag' );
	
	// Custom header
	/*$args = array(
		'width'         => 1920,
		'height'        => 1080,
		'default-image' => get_template_directory_uri() . '/images/bg.jpg',
		'uploads'       => true,
		'header-text'  	=> false
	);
	add_theme_support( 'custom-header', $args );*/
	
	// Post formats
	//add_theme_support( 'post-formats', array( 'gallery' ) );
		
	// Jetpack infinite scroll
	/*add_theme_support( 'infinite-scroll', array(
		'type' 				=> 		'click',
	    'container'			=> 		'posts',
	    'wrapper'			=>		false,
		'footer' 			=> 		false,
	) );*/
	
	// Add nav menu
	register_nav_menu( 'primary', __('Primary Menu','urstein') );
}// Display line breaks in all contentsfunction keep_breaks_in_content( $content ){	$content = nl2br($content);    return $content;}add_filter( 'the_content', 'keep_breaks_in_content', 0 );
// Register and enqueue Javascript files
function urstein_load_javascript_files() {
	if ( !is_admin() ) { // scripts used on non-admin area only
		wp_enqueue_script( 'urstein_flexslider', get_template_directory_uri().'/js/flexslider.js', array('jquery'), '', true );
		wp_enqueue_script( 'urstein_doubletaptogo', get_template_directory_uri().'/js/doubletaptogo.js', array('jquery'), '', true );
		wp_enqueue_script( 'urstein_global', get_template_directory_uri().'/js/global.js', array('jquery'), '', true );		
		/*if ( is_singular() ) { 
			wp_enqueue_script( "comment-reply" );
		}*/
	}	if (is_singular('event') || is_singular('camp') || is_page('pfadiheim')){		wp_enqueue_script('search_ch_map', '//map.search.ch/api/map.js');	}
}
add_action( 'wp_enqueue_scripts', 'urstein_load_javascript_files' );
// Register and enqueue styles
function urstein_load_style() {
	if ( !is_admin() ) {		wp_enqueue_style( 'dashicons' );
	    wp_enqueue_style( 'urstein_googleFonts', '//fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Droid+Serif:400,400italic,700,700italic' );
	    wp_enqueue_style( 'urstein_fontawesome', get_stylesheet_directory_uri() . '/fa/css/font-awesome.css' );
	    wp_enqueue_style( 'urstein_style', get_stylesheet_uri() );
	}
}
add_action('wp_print_styles', 'urstein_load_style');
// Add editor styles
function urstein_add_editor_styles() {
    add_editor_style( 'urstein-editor-styles.css' );
    $font_url = '//fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Droid+Serif:400,400italic,700,700italic';
    add_editor_style( str_replace( ',', '%2C', $font_url ) );
}
add_action( 'init', 'urstein_add_editor_styles' );
// Add admin stylesfunction load_admin_styles() {	if ( is_admin() ) {		wp_enqueue_style( 'admin_style', get_template_directory_uri() . '/admin/admin-style.css');		wp_enqueue_style( 'admin_event', get_template_directory_uri() . '/admin/event.css');		wp_enqueue_style( 'admin_attendee', get_template_directory_uri() . '/admin/attendees.css');	}}  
add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
// Check whether the browser supports javascript
function html_js_class () {
    echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
}
add_action( 'wp_head', 'html_js_class', 1 );

// Urstein archive navigation function
function urstein_archive_navigation() {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<div class="archive-nav">
			<?php 
				if ( get_previous_posts_link() ) {
					previous_posts_link( '<span class="fa fw fa-angle-left"></span>' );
				} else {
					echo '<span class="fa fw fa-angle-left"></span>';
				} 
			?>
			<span class="sep">/</span>
			<?php 
				if ( get_next_posts_link() ) {
					next_posts_link( '<span class="fa fw fa-angle-right"></span>' );
				} else {
					echo '<span class="fa fw fa-angle-right"></span>';
				} 
			?>
			<div class="clear"></div>
		</div> <!-- /archive-nav-->
	<?php endif;
}

// Style the admin area
function urstein_admin_area_style() { 
   echo '
<style type="text/css">

	#postimagediv #set-post-thumbnail img {
		max-width: 100%;
		height: auto;
	}

</style>';
}

add_action('admin_head', 'urstein_admin_area_style');
// Add body classes to single pages
add_filter('body_class','urstein_post_class_to_page');
 
function urstein_post_class_to_page( $classes ){
    if ( is_page() || is_404() || ( is_search() && !have_posts() ) ) {
        $classes[] = 'post single';
    } 
    return $classes;
}
// Add body class if is mobile
add_filter('body_class','urstein_is_mobile_body_class');
 
function urstein_is_mobile_body_class( $classes ){
    if ( wp_is_mobile() ) {
        $classes[] = 'wp-is-mobile';
    }
    return $classes;
}
// Flexslider function for format-gallery
function urstein_flexslider($size) {
	if ( is_page()) :
		$attachment_parent = $post->ID;
	else : 
		$attachment_parent = get_the_ID();
	endif;	if($images = get_posts(array(
		'post_parent'    => $attachment_parent,
		'post_type'      => 'attachment',
		'numberposts'    => -1, // show all
		'post_status'    => null,
		'post_mime_type' => 'image',
                'orderby'        => 'menu_order',
                'order'           => 'ASC',
	))) { ?>
		<div class="flexslider">
			<ul class="slides">
				<?php foreach($images as $image) { 
					$attimg = wp_get_attachment_image($image->ID, $size); ?>
					<li>
						<?php echo $attimg; ?>
					</li>
				<?php }; ?>
			</ul>
		</div><?php
	}
}
// urstein theme options
class urstein_Customize {

   public static function urstein_register ( $wp_customize ) {		// Background image and header
      // 1. Define a new section to the Theme Customizer
      $wp_customize->add_section( 'urstein_logo_section' , array(
		    'title'       => __( 'Kopfzeile und Hintergrund', 'urstein' ),
		    'priority'    => 40,
		    'description' => __('Wähle Bilder für die Titelzeile und den Hintergrund.', 'urstein'),
	  ) );
      // 2. Register new settings to the WP database...
	  $wp_customize->add_setting( 'urstein_logo', array( 'sanitize_callback' => 'esc_url_raw') );	  $wp_customize->add_setting( 'urstein_background', array( 'sanitize_callback' => 'esc_url_raw') );	 
      // 3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'urstein_logo', array(
		    'label'    => __( 'Bild Kopfzeile', 'urstein' ),
		    'section'  => 'urstein_logo_section',
		    'settings' => 'urstein_logo',
	  ) ) );	 $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'urstein_background', array(		    'label'    => __( 'Hintergrundbild', 'urstein' ),		    'section'  => 'urstein_logo_section',		    'settings' => 'urstein_background',	  ) ) );	 	  
      // 4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';	  /* Custom default images for Events, News, and Camps */	  // 1. Define a new section to the Theme Customizer      $wp_customize->add_section( 'urstein_custom_img_section' , array(		    'title'       => __( 'Anzeigebilder', 'urstein' ),		    'priority'    => 40,		    'description' => __('Diese Bilder werden auf der Hauptseite angezeigt, falls kein anderes Bild verfügbar ist für einen Beitrag.', 'urstein'),	  ) );      // 2. Register 3 new settings to the WP database...	  $wp_customize->add_setting( 'urstein_custom_img_event');	  $wp_customize->add_setting( 'urstein_custom_img_camp');	  $wp_customize->add_setting( 'urstein_custom_img_news');      // 3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...      $wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'urstein_custom_img_event', array(		    'label'    => __( 'Übungen', 'urstein' ),		    'section'  => 'urstein_custom_img_section',		    'settings' => 'urstein_custom_img_event'	  ) ) );	  $wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'urstein_custom_img_camp', array(		    'label'    => __( 'Lager', 'urstein' ),		    'section'  => 'urstein_custom_img_section',		    'settings' => 'urstein_custom_img_camp'	  ) ) );	  $wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'urstein_custom_img_news', array(		    'label'    => __( 'News', 'urstein' ),		    'section'  => 'urstein_custom_img_section',		    'settings' => 'urstein_custom_img_news'	  ) ) );
   }

   public static function urstein_header_output() {
      ?>
	      <!-- Customizer CSS --> 
	      <style type="text/css">
	           <?php self::urstein_generate_css('body a', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('body a:hover', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.blog-title a:hover', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post:hover .archive-post-title', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content a', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content a:hover', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content a:hover', 'border-bottom-color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content p.pull', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content input[type="submit"]', 'background', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content input[type="button"]', 'background', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content input[type="reset"]', 'background', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content input:focus', 'border-color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.post-content textarea:focus', 'border-color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.button', 'background', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.page-links a:hover', 'background', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.comments .pingbacks li a:hover', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.comment-header h4 a:hover', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.comment-form input:focus', 'border-color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.comment-form textarea:focus', 'border-color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.form-submit #submit', 'background-color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.comment-title .url:hover', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.comment-actions a', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.comment-actions a:hover', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.archive-nav a:hover', 'color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('#infinite-handle:hover', 'background', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.credits p:first-child a:hover', 'color', 'accent_color'); ?>
	           
	           <?php self::urstein_generate_css('.nav-toggle.active .bar', 'background-color', 'accent_color'); ?>
	           <?php self::urstein_generate_css('.mobile-menu a:hover', 'color', 'accent_color'); ?>
	           
	      </style> 
	      
	      <!--/Customizer CSS-->
	      
      <?php
   }
   
   public static function urstein_live_preview() {
      wp_enqueue_script( 
           'urstein-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

   public static function urstein_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}
// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'urstein_Customize' , 'urstein_register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'urstein_Customize' , 'urstein_header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'urstein_Customize' , 'urstein_live_preview' ) );// Some utility functions
function get_auto_increment(){	static $counter = 0;	return $counter++;}
?>