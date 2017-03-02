<?php
/**
 * Plugin Name: hookdump-taeyoon
 * Description: hookdump example by taeyoon
 * Author: Taeyoon Moon 
 * Version: 1.0
*/

/* adds admin menu */
add_action( 'admin_menu', 'taeyoon_add_admin_menu' );

/* our customized action hook */
add_action( 'hookdump_taeyoon', 'taeyoon_hook_dump' );

/* admin_menu action callback */
function taeyoon_add_admin_menu() {
    add_menu_page(
        'hookdump-taeyoon',
        'hookdump-taeyoon',
        'manage_options',
        'hookdump-taeyoon',
        'taeyoon_admin_menu_page_callback'
    );
}
/* add_menu_page callback */
function taeyoon_admin_menu_page_callback() {
    if( function_exists( 'dump_all_hooks' ) ) {
      dump_all_hooks();
    } else {
      echo '<p>My master plugin is not activated.</p>';
    }
    do_action( 'hookdump_taeyoon' );
}
/* our callback function might be here....*/
function taeyoon_hook_dump() {
    echo "<div>Wow! There are so many hooks in the WordPress!</div>";
}
