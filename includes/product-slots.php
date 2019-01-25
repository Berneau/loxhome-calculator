<?php
  define('RELAY_EXTENSION', 14);
  define('AIR_BASE_EXTENSION', 120);
  define('DI_EXTENSION', 20);
  define('TREE_EXTENSION', 90);
  define('NANO_DI_TREE', 6);
  define('EXTENSION', 8);
  define('12_KANAL_VERSTAERKER', 12);
  define('DIMMER_EXTENESION', 14);
  define('RGBW_24V_DIMMER', 4);
  define('LED_SPOT_WW_V2', 10);
  
  function lxhm_request_skus_from_article($type, $option) {
    $relations_string = file_get_contents(LXHM_PLUGIN_DIR . '/includes/sku-relations.json');
    $relations_json = json_decode($relations_string);
    return $relations_json->$type->$option;
  }
?>