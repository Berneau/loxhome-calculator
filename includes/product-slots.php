<?php
  function lxhm_get_amount_by_slots($sku, $amount) {
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
    
    // amount does not exceed slot limit
    if ($slots[$sku] >= $amount) return 1;
    
    $base = intdiv($amount, $slots[$sku]);
    $remainder = $amount % $slots[$sku];
    if ($remainder != 0) $base++;
    return $base;
  }
  
  function lxhm_request_skus_from_article($type, $option) {
    $relations_string = file_get_contents(LXHM_PLUGIN_DIR . '/includes/sku-relations.json');
    $relations_json = json_decode($relations_string);
    return $relations_json->$type->$option;
  }
?>