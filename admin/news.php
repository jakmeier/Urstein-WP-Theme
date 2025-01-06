<?php
/* Custom post type news
 * No need for meta values, simply using the standard
 * field for a post is enough.
 * (Actually, a normal post would be enoug but for flexibility
 *   later on I desgined it as custom post type. Also, it allows
 *   for cutsom capabilities on news rather than using the post caps)
 */
 
if(!function_exists('create_news_post_type')):
	function create_news_post_type() {
		$args=array(
			'label' => 'News',
			'public' => true,
			'publicly_queryable' => true,
			'show_in_rest' => true,
			'show_ui' => true,
			'capabilities' => array(
				'edit_post'          => 'edit_news', 
				'read_post'          => 'edit_news', 
				'delete_post'        => 'edit_news', 
				'edit_posts'         => 'edit_news', 
				'edit_others_posts'  => 'edit_news', 
				'publish_posts'      => 'edit_news',       
				'read_private_posts' => 'edit_news', 
				'create_posts'       => 'edit_news', 
				'delete_posts'       => 'edit_news',
			),
			'hierarchical' => false,
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			),
			'menu_position' => 5,
			'menu_icon' => 'dashicons-email-alt',
		);
		register_post_type('news', $args);
	}
	add_action('init','create_news_post_type');

endif;
?>