<?php
/* Custom post type news
 * No need for meta values, simply using the standard
 * field for a post is enough.
 * (Actually, a normal post would be enoug but for flexibility
 *   later on I desgined it as custom post type)
 */
 
if(!function_exists('create_news_post_type')):
	function create_news_post_type() {
		$args=array(
			'label' => 'News',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			),
			'menu_position' => 5,
		);
		register_post_type('news', $args);
	}
	add_action('init','create_news_post_type');

endif;
?>