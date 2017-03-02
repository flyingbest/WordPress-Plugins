<?php
/*
Plugin Name: hookdump-<author>
Description: hookdump example by <author>
Author: <your name>
*/
/* adds admin menu */
add_action( 'admin_menu', '<author>_add_admin_menu' );
/* our customized action hook */
add_action( 'hookdump_<author>', ... );
/* admin_menu action callback */
function <author>_add_admin_menu() {
    add_menu_page(
        'hookdump-<author>',
        'hookdump-<author>',
        'manage_options',
        'hookdump-<author>',
        '<author>_admin_menu_page_callback'
    );
}
/* add_menu_page callback */
function <author>_admin_menu_page_callback() {
    if( function_exists( 'dump_all_hooks' ) ) {
      dump_all_hooks();
    } else {
      echo '<p>My master plugin is not activated.</p>';
    }
    do_action( 'hookdump_<author>' );
}
/* our callback function might be here....*/
function ...() {
    echo "<div>Wow! There are so many hooks in the WordPress!</div>";
}
