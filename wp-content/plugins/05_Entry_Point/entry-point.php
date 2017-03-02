<?php
/**
 * Plugin Name: entry-points-taeyoon
 */

// Menu entry points
add_action( 'admin_menu', 'taeyoon_entry_points_add_admin_menu' );

function taeyoon_entry_points_add_admin_menu() {

  add_menu_page(
      'taeyoon_menu',
      'taeyoon_menu',
      'read',
      'taeyoon_menu',
      'taeyoon_entry_points_add_menu_page_callback'
      );

  add_submenu_page(
      'taeyoon_menu',
      'taeyoon_submenu',
      'taeyoon_submenu',
      'read',
      'taeyoon_submenu',
      'taeyoon_entry_points_add_submenu_page_callback'
      );
}

// add_menu_page callback
function taeyoon_entry_points_add_menu_page_callback() { ?>
  <div class="wrap">
    <h3>POST 방식 전송의 예제입니다.</h3>
    <form method="post" action="<?=admin_url('admin-post.php')?>">
    <label for="item">Item</label>
    <input type="text" id="item" name="item" value="" />
    <input type="hidden" name="action" value="taeyoon_admin_post_action" />
    <input class="button button-primary" type="submit" />
    </form>
    </div>
    <?php
}

// add_submenu_page callback
function taeyoon_entry_points_add_submenu_page_callback() { ?>
  <script>
    function send_form() {
      jQuery.post(
	  '<?=admin_url( 'admin-ajax.php' )?>',
	  jQuery('form[name="item_form"]').serialize(),
	  function(data) {
	  jQuery('span#item_name').hide().html(data).fadeIn(200);
	  }
	  );
      return false;
    }
  </script>
    <div class="wrap">
    <h3>AJAX 방식 전송의 예제입니다.</h3>
    <form name='item_form'>
    <label for="item">Item</label>
    <input type="text" id="item" name="item" value="" />
    <input type="hidden" name="action" value="taeyoon_ajax_action" />
    <input class="button button-primary" type="submit" onclick="return send_form();"/>
    </form>
    <div>
    <span id="item_name"></span>
    </div>
    </div>
    <?php
}

// admin-post
add_action( 'admin_post_taeyoon_admin_post_action', 'taeyoon_entry_points_admin_post_callback' );

function taeyoon_entry_points_admin_post_callback() {
  echo $_REQUEST['item'];
  die();
}

// ajax
add_action( 'wp_ajax_taeyoon_ajax_action', 'taeyoon_entry_points_ajax_action_callback' );

function taeyoon_entry_points_ajax_action_callback() {
  echo $_REQUEST['item'];
  die();
}

// redirect
add_action( 'template_redirect', 'taeyoon_entry_points_redirect_callback' );

function taeyoon_entry_points_redirect_callback() {

  if( $_GET['rocket'] == 'taeyoon' ) {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8" /></head><body>템플릿이 완전히 재정의되지요. by taeyoon.</body></html>';
    die();
  }
}

// shortcode
add_shortcode( 'taeyoon_shortcode', 'taeyoon_shortcode_callback' );

function taeyoon_shortcode_callback() {

  return "taeyoon shortcode successfully called!";
}

