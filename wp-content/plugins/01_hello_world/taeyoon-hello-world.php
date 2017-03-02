<?php
/**
 * Plugin Name: taeyoon-hello-world
 * Description: This is taeyoon's plugin
 * Author: Taeyoon Moon
 * Version: 1.0
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

add_action(
	'admin_notice',
	function() {
		echo '<div class="notice notice-success"><p>Hello, taeyoon!</p></div>';
	}
);


