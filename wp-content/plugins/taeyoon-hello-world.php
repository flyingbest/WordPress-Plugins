<?php
/**
 * Plugin Name: taeyoon-hello-world
 * Description: This is taeyoon's plugin
 */

add_action(
	'admin_notice',
	function() {
		echo '<div class="notice notice-success"><p>Hello, taeyoon!</p></div>';
	}
);


