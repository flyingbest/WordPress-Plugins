<?php
/*
 * Plugin Name: custom-post-taeyoon
 * Author: taeyoon
 * Description: This is simple testing custom-post.
 */

add_action('init', 'taeyoon_register_post_type');

function taeyoon_register_post_type(){
	$args = array(
		'label' => 'taeyoons',
		'public' => TRUE,
		'show_in_admin_bar' => FALSE,
		'register_meta_box_cb' => 'taeyoon_meta_box_cb',
	);
	
	register_post_type('taeyoon_custom', $args);
}

function taeyoon_meta_box_cb(){
	add_meta_box(
		'taeyoon-metabox', '태윤 메타 박스',
		'taeyoon_output_meta_box', 'taeyoon_custom'
	);
}

function taeyoon_output_meta_box(){
	global $post;
	$post_id = $post->ID;
	$meta_value = esc_attr(get_post_meta($post_id, 'taeyoon_meta_key', TRUE));
	echo "<label for='taeyoon-meta-key'> META KEY </label>";
	echo "<input id=\"taeyoon-meta-key\" type=\"text\" name=\"taeyoon_meta_key\" value=\"{$meta_value}\">";
	echo "{$meta_value}";
}

add_action('save_post', 'taeyoon_save_post', 10, 1);

function taeyoon_save_post( $post_id ){
	if(isset($_POST[ 'taeyoon_meta_key' ])){
		$meta_value = sanitize_text_field($_POST[ 'taeyoon_meta_key' ]);
	}else{
		$meta_value = '';
	}

	update_post_meta($post_id, 'taeyoon_meta_key', $meta_value);
}

