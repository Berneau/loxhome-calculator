<?php
function lxhm_add_to_cart() {
  $products = $_POST['products'];
  if (!$products) echo null;
  
  var_dump($products);
  
  foreach ($products as $product) {
    WC()->cart->add_to_cart((int)$product['id'], (int)$product['amount']);
  }
  
  wp_die();
}
?>