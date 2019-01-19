<?php
function lxhm_add_room(){
  echo 'Test';
  wp_die();
}

add_action( "wp_ajax_lxhm_add_room", "lxhm_add_room" );
add_action( "wp_ajax_nopriv_lxhm_add_room", "lxhm_add_room" );
?>