<?php
include_once LXHM_PLUGIN_DIR . '/includes/product-slots.php';

function lxhm_calculate_rooms() {
  
  $decodedData = lxhm_decode_data($_POST['formData']);
  if (!$decodedData) return;

  // lxhm_get_server_type_sku($decodedData->serverType);
  $rooms_skus = lxhm_loop_over_rooms($decodedData->rooms);
  
  print_r($rooms_skus);
  
  wp_die();
}

function lxhm_decode_data($data) {
  // removes the slashes added by WP
  $unslashedData = wp_unslash($data);
  return json_decode($unslashedData);
}

// function lxhm_get_server_type_sku($type) {
//   if ($data->serverType == 'miniserver') return array('100001' => 1);
//   if ($data->serverType == 'miniserver-go') return array('100139' => 1);
// }


function lxhm_loop_over_rooms($rooms) {
  $sku_to_add = array();
  $sku_for_slot = array();
  
  foreach ($rooms as $room) {
  
    foreach ($room->articles as $article) {
  
      $requirements = lxhm_request_skus_from_article($article->type, $article->option);
    }
  
  }
  
  return $requirements;
}














// function lxhmGetProductsFromWC() {
//   return wc_get_products(array());
// }
// 
// function lxhmGetProductTemplate() {
//   ob_start();
//   include LXHM_PLUGIN_DIR . '/templates/product.template.php';
//   $html .= ob_get_contents();
//   ob_end_clean();
// }

add_action( "wp_ajax_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
add_action( "wp_ajax_nopriv_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
?>