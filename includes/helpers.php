<?php
function lxhm_decode_data($data) {
  // removes the slashes added by WP
  $unslashedData = wp_unslash($data);
  return json_decode($unslashedData);
}

function lxhm_get_slots_by_sku($sku) {
  $slots = array(
    '100038' => 14,
    '100114' => 120,
    '100283' => 20,
    '100218' => 90,
    '100242' => 6,
    '100002' => 8,
    '200110' => 12,
    '100029' => 14,
    'rgbw-24v-dimmer' => 4,
    'led-spot-ww' => 10
  );
  return $slots[$sku];
}

function lxhm_request_skus_from_article($type, $option, $serverType) {
  $relations_string;
  if ($serverType == 'miniserver') $relations_string = file_get_contents(LXHM_PLUGIN_DIR . '/includes/sku-miniserver.json');
  elseif ($serverType == 'miniserver-go') $relations_string = file_get_contents(LXHM_PLUGIN_DIR . '/includes/sku-miniserver-go.json');
  $relations_json = json_decode($relations_string);
  return $relations_json->$type->$option;
}

function lxhm_get_html_from_skus($skus) {
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
?>