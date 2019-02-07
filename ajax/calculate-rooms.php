<?php
include_once LXHM_PLUGIN_DIR . '/includes/product-slots.php';

function lxhm_calculate_rooms() {
  
  $decodedData = lxhm_decode_data($_POST['formData']);
  if (!$decodedData) return;

  $server_sku = lxhm_get_server_type_sku($decodedData->serverType);
  $rooms_skus = lxhm_loop_over_rooms($decodedData->rooms);
  
  $overall_skus = array();
  
  // add server to overall list of skus
  $overall_skus[$server_sku] = 1;
  
  // add new items to overall list of skus
  foreach ($rooms_skus->add as $key => $value) {
    $overall_skus[$key] = $value;
  }
  
  // check if slots are exceeding capacity -> add extras if necessary
  foreach ($rooms_skus->slots as $key => $value) {
    $amount = lxhm_get_amount_by_slots($key, $value);
    $overall_skus[$key] = $amount;
  }
  
  $products = lxhm_get_products_by_skus($overall_skus);
  
  echo json_encode($products);
  wp_die();
}

function lxhm_decode_data($data) {
  // removes the slashes added by WP
  $unslashedData = wp_unslash($data);
  return json_decode($unslashedData);
}

function lxhm_get_server_type_sku($type) {
  if ($type == 'miniserver-go') return '100139';
  return '100001';
}

function lxhm_loop_over_rooms($rooms) {
  $sku_to_add = array();
  $sku_for_slot = array();
  
  foreach ($rooms as $room) {
    foreach ($room->articles as $article) {
  
      // TODO: if at least 1 Jalousie -> add wetterstation
      // TODO: slots from 8 and 12 are only available to each seperate room
      // TODO: if 14 or 15 is selected, requires at least one 10, except 16 is selected
      // TODO: change product type for each exceeded step for 17( 4/8/12/16/20)
      // TODO: for each ten 27 spots, add 1 channel
      // TODO: if either musik, lights or heating in room, add one motion detector
      // TODO: calculate speakers -> probably not set as "new"
      
      $requirements = lxhm_request_skus_from_article($article->type, $article->option);
      
      
      // handle slots needed
      for ($i = 0; $i < sizeof($requirements->slots); $i++) {
        foreach ($requirements->slots[$i] as $key => $value) {
          if (!isset($sku_for_slot[$key])) $sku_for_slot[$key] = 0;
          $sku_for_slot[$key] += ($value * $article->amount);
        }
      }
      
      // handle items to add
      for ($i = 0; $i < sizeof($requirements->new); $i++) {
        foreach ($requirements->new[$i] as $key => $value) {
          if (!isset($sku_to_add[$key])) $sku_to_add[$key] = 0;
          $sku_to_add[$key] += ($value * $article->amount);
        }
      }

    }
  }
  
  $return_obj = new stdClass();
  $return_obj->slots = $sku_for_slot;
  $return_obj->add = $sku_to_add;
  return $return_obj;
}


function lxhm_get_products_by_skus($skus) {
  $html = '';
  $sum = 0.00;
  $products = array();
  
  foreach ($skus as $key => $value) {
    // workaround for get product by sku
    $product_id = wc_get_product_id_by_sku($key);
    $product = wc_get_product($product_id);
    
    // add price of product to overall sum
    $sum += ($product->price * $value);
    $html .= lxhm_build_product_template($product, $value);
    
    $simple_product = new stdClass();
    $simple_product->id = $product_id;
    $simple_product->amount = $value;
    array_push($products, $simple_product);
  }
  
  $html .= lxhm_build_sum_template($sum);
  
  $return_obj = new stdClass();
  $return_obj->html = $html;
  $return_obj->products = $products;
  return $return_obj;
}

function lxhm_build_product_template($product, $amount) {
  if (!$product) return null;
  $html = '';
  
  $product->lxhm_thumbnail_url = wp_get_attachment_image_src($product->image_id, 'thumbnail');
  $full_amount = $amount * $product->price;
  
  ob_start();
  include LXHM_PLUGIN_DIR . '/templates/product.template.php';
  $html .= ob_get_contents();
  ob_end_clean();
  
  return $html;
}

function lxhm_build_sum_template($sum) {
  $html = '<li class="lxhm-sum-product"><div class="lxhm-product-spacer"></div>';
  $html .= '<div>';
  $html .= $sum;
  $html .= '€</div>';
  $html .= '</li>';
  return $html;
}


add_action( "wp_ajax_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
add_action( "wp_ajax_nopriv_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
?>