<?php
include_once LXHM_PLUGIN_DIR . '/includes/helpers.php';

function lxhm_calculate_rooms() {
  
  $decodedData = lxhm_decode_data($_POST['formData']);
  if (!$decodedData) return;
  
  $skus;
  if ($decodedData->serverType == 'miniserver') $skus = lxhm_miniserver_path($decodedData->rooms);
  else if ($decodedData->serverType == 'miniserver-go') $skus = lxhm_miniserver_go_path($decodedData->rooms);
  
  $return_obj = lxhm_get_html_from_skus($skus);
  echo json_encode($return_obj);
  wp_die();
}

function lxhm_miniserver_path($rooms) {
  $skus = array();
  $temp_slots = array();
  
  // add servertype sku
  $skus['100001'] = 1;
  
  // loop over rooms and articles
  foreach ($rooms as $room) {
    foreach ($room->articles as $article) {
      
      // get sku from json file
      $requirements = lxhm_request_skus_from_article($article->type, $article->option, 'miniserver');
      
      // handle new additions
      foreach ($requirements->new as $new) {
        if (!isset($skus[$new->sku])) $skus[$new->sku] = 0;
        $skus[$new->sku] += ($article->amount * $new->amount);
      }
      
      // handle slot additions
      foreach ($requirements->slots as $slots) {
        if (!isset($temp_slots[$slots->sku])) $temp_slots[$slots->sku] = 0;
        $temp_slots[$slots->sku] += ($article->amount * $slots->amount);
      }
      
      // TODO: if at least 1 Jalousie -> add wetterstation
      // TODO: slots from 8 and 12 are only available to each seperate room
      // TODO: if 14 or 15 is selected, requires at least one 10, except 16 is selected
      // TODO: change product type for each exceeded step for 17( 4/8/12/16/20)
      // TODO: for each ten 27 spots, add 1 channel
      // TODO: if either musik, lights or heating in room, add one motion detector
      // TODO: calculate speakers -> probably not set as "new"
    }
  }
  
  // add new items considering slots
  foreach ($temp_slots as $key => $value) {
    $slots_available = lxhm_get_slots_by_sku($key);
    $to_add = 0;
    
    // one item offers enough slots -> add item
    if ($value <= $slots_available) $to_add = 1;
    // not enough slots -> add multiple items
    else {
      $to_add = intdiv($value, $slots_available);
      $remainder = $value % $slots_available;
      if ($remainder != 0) $to_add++;
    }
    
    if (!isset($skus[$key])) $skus[$key] = 0;
    $skus[$key] += $to_add;
  }

  return $skus;
}

function lxhm_miniserver_go_path($rooms) {
  $skus = array();
  $temp_slots = 0;

  // add servertype sku
  $skus['100139'] = 1;
  
  // loop over rooms and articles
  foreach ($rooms as $room) {
    foreach ($room->articles as $article) {
      
      // get sku from json file
      $requirements = lxhm_request_skus_from_article($article->type, $article->option, 'miniserver-go');
      
      // handle new additions
      foreach ($requirements->new as $new) {
        if (!isset($skus[$new->sku])) $skus[$new->sku] = 0;
        $skus[$new->sku] += ($article->amount * $new->amount);
      }
      
      // handle slot additions
      foreach ($requirements->slots as $slots) {
        if (!isset($skus[$slots->sku])) $skus[$slots->sku] = 0;
        $skus[$slots->sku] += ($article->amount * $slots->amount);
        $temp_slots++;
      }
      
      // TODO: add extra rules
    }
  }
  
  // TODO: if more than 120 $temp_slots -> add one more miniserver-go
  
  return $skus;
}





add_action( "wp_ajax_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
add_action( "wp_ajax_nopriv_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
?>