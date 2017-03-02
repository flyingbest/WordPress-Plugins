<?php
/*
Plugin Name: hookdump-master
Description: hookdump example by master. Use it under WP4.7+
Author: changwoo
*/

if( !function_exists( 'dump_all_hooks' ) ) {
  function dump_all_hooks() {
    global $wp_filter;
    
    $total_count = 0;
    
    foreach( $wp_filter as $tag => $obj ) {
      echo "<h3>Hook: $tag</h3>";
      echo '<table class="table widefat">';
      echo '<thead><tr>';
      echo '<td>Proirity</td>';
      echo '<td>Callback function Name</td>';
      echo '<td>Number of arguments</td>';
      echo '</tr></thead>';
      echo '<tbody>';
      foreach( $obj->callbacks as $priority => $callbacks ) {
        echo '<tr>';
        echo '<td rowspan="' . count( $callbacks ) . "\">$priority</td>";
        $rowspan = false;
        foreach( $callbacks as $cb ) {
          if( $rowspan ) {
            echo '<tr>';
          } 
          $func = $cb['function'];
          if( is_array( $func ) ) {
            $class  = $func[0];
            $method = $func[1]; 
            if( is_object( $class ) ) {
              echo '<td>' . get_class( $obj ) . "::$method</td>";
            } else {
              echo '<td>' . $class . "::$method</td>";
            }
          } else {
            echo "<td>$func</td>";
          }
          ++$total_count;
          echo "<td>{$cb['accepted_args']}</td>";
          echo '</tr>';
          $rowspan = true;
        }      
      }
      echo "</tbody></table>\n";
    }
    echo "<p>There are $total_count hooks in this WordPress site. Wow, that's huge!</p>";
  }
}

/* adds admin menu */
add_action( 'admin_menu', 'master_add_admin_menu' );

/* our customized action hook */
add_action( 'master_hookdump', 'master_hookdump_callback' );

/* admin_menu action callback */
function master_add_admin_menu() {
    add_menu_page(
        'hookdump-master',
        'hookdump-master',
        'manage_options',
        'hookdump-master',
        'master_admin_menu_page_callback'
    );
}

/* add_menu_page callback */
function master_admin_menu_page_callback() {
    
    dump_all_hooks();
    
    /* your customized hook here */
    do_action( 'master_hookdump' );
}


/* our callback function might be here....*/
function master_hookdump_callback() {
    echo "<div>Wow! There are so many hooks in the WordPress!</div>";
}
