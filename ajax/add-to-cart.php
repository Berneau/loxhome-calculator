<?php
function lxhm_add_to_cart() {
  $products = $_POST['products'];
  if (!products) echo null;
  
  foreach ($products as $product) {
    WC()->cart->add_to_cart((int)$product['id'], (int)$product['amount']);
  }
  
  wp_die();
}

add_action( "wp_ajax_lxhm_add_to_cart", "lxhm_add_to_cart" );
add_action( "wp_ajax_nopriv_lxhm_add_to_cart", "lxhm_add_to_cart" );
?>