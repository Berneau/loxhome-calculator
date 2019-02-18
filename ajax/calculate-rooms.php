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
  
  $needs_weather_station = false;
  $amount_of_motion_detectors = 0;
  $amount_of_speaker_rooms = 0;
  $needs_music_server = false;
  $is_selected_14_or_15 = false;
  $is_selected_16 = false;
  $amount_of_di_trees = 0;
  $amount_of_ww_spots = 0;
  
  // add servertype sku
  $skus['100001'] = 1;
  
  // loop over rooms and articles
  foreach ($rooms as $room) {
    
    $needs_motion_detector = false;
    $has_speaker_in_room = false;
    $amount_of_di_tree_slots_room = 0;
    
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
      
      // handle special cases
      // if at least 1 jalousie -> add 1 weather station
      if ($article->type == 'jalousie') $needs_weather_station = true;
      
      // if either musik, lights or heating in room, add one motion detector
      if ($article->type == 'raumregelung' ||
          $article->type == 'speaker' ||
          $article->type == 'universalbeleuchtung') $needs_motion_detector = true;
      
      // add to total of rooms with speakers
      if ($article->type == 'speaker') $has_speaker_in_room = true;
      
      // if 14 or 15 is selected, requires at least one 10, except 16 is selected
      if ($article->type == 'raumregelung') {
        if ($article->option == 1 || $article->option == 2) $is_selected_14_or_15 = true;
        if ($article->option == 3) $is_selected_16 = true;
      }
      
      // slots from 8 and 12 are only available to each seperate room
      if ($article->type == 'fenster' && $article->option == 4) {
        $amount_of_di_tree_slots_room += $article->amount;
      }
      
      if ($article->type == 'innentuer' && $article->option == 4) {
        $amount_of_di_tree_slots_room += $article->amount;
      }
      
      // for each ten 27 spots, add 1 channel
      if ($article->type == 'loxone_lights' && $article->option == 3) {
        $amount_of_ww_spots += $article->amount;
      }
    }
    
    if ($needs_motion_detector) $amount_of_motion_detectors++;
    if ($has_speaker_in_room) $amount_of_speaker_rooms++;
    if ($amount_of_di_tree_slots_room > 0) {
      $to_add = intdiv($amount_of_di_tree_slots_room, 6);
      $remainder = $amount_of_di_tree_slots_room % 6;
      if ($remainder !=0) $to_add++;
      $amount_of_di_trees += $to_add;
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
  
  if ($needs_weather_station) $skus['100246'] = 1;
  if ($amount_of_motion_detectors > 0) $skus['motion-sensor'] = $amount_of_motion_detectors;
  if ($amount_of_speaker_rooms > 0) {
    if ($amount_of_speaker_rooms <= 4) $skus['100165'] = 1;
    if ($amount_of_speaker_rooms > 4 && $amount_of_speaker_rooms <= 8) $skus['100166'] = 1;
    if ($amount_of_speaker_rooms > 8 && $amount_of_speaker_rooms <= 12) $skus['100167'] = 1;
    if ($amount_of_speaker_rooms > 12 && $amount_of_speaker_rooms <= 16) $skus['100168'] = 1;
    if ($amount_of_speaker_rooms > 16) $skus['100169'] = 1;
    
    $to_add = intdiv($amount_of_speaker_rooms, 12);
    $remainder = $amount_of_speaker_rooms % 12;
    if ($remainder !=0) $to_add++;
    
    if (!isset($skus['200110'])) $skus['200110'] = 0;
    $skus['200110'] = $to_add;
  }
  if ($is_selected_14_or_15 && !$is_selected_16) {
    if (!isset($skus['100218'])) $skus['100218'] = 0;
    $skus['100218'] += 1;
  }
  if ($amount_of_di_trees > 0) {
    if (!isset($skus['100242'])) $skus['100242'] = 0;
    $skus['100242'] += $amount_of_di_trees;
  }
  if ($amount_of_ww_spots > 0) {
    $dimmer_slots_needed = intdiv($amount_of_ww_spots, 10);
    $remainder = $amount_of_ww_spots % 10;
    if ($remainder !=0) $dimmer_slots_needed++;
    
    $to_add = intdiv($dimmer_slots_needed, 4);
    $remainder = $dimmer_slots_needed % 4;
    if ($remainder !=0) $to_add++;
    
    if (!isset($skus['rgbw-24v-dimmer'])) $skus['rgbw-24v-dimmer'] = 0;
    $skus['rgbw-24v-dimmer'] += $to_add;
  }

  return $skus;
}

function lxhm_miniserver_go_path($rooms) {
  $skus = array();
  $temp_slots = 0;
  
  $needs_weather_station = false;
  $needs_nano_io_air = false;
  $amount_of_motion_detectors = 0;
  $amount_of_speaker_rooms = 0;
  $needs_music_server = false;
  $amount_of_rgbw_spots = 0;
  $amount_of_ww_spots = 0;

  // add servertype sku - amount gets added later
  $skus['100139'] = 1;
  
  // loop over rooms and articles
  foreach ($rooms as $room) {
    
    $needs_motion_detector = false;
    $has_speaker_in_room = false;
    
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
      
      // handle special cases
      // if at least 1 jalousie -> add 1 weather station
      if ($article->type == 'jalousie') $needs_weather_station = true;
      
      // if either musik, lights or heating in room, add one motion detector
      if ($article->type == 'raumregelung' ||
          $article->type == 'speaker' ||
          $article->type == 'universalbeleuchtung') $needs_motion_detector = true;
      
      // add to total of rooms with speakers
      if ($article->type == 'speaker') $has_speaker_in_room = true;
      
      // 5 needs at least 1 nano-io-air
      if ($article->type == 'fenster' && $article->option == 1) {
        $needs_nano_io_air = true;
      }
      
      // only add half of nano io airs for ein/aus
      if ($article->type == 'universalbeleuchtung' && $article->option == 1) {
        $to_add = intdiv($article->amount, 2);
        $remainder = $article->amount % 2;
        if ($remainder !=0) $to_add++;
        
        if (!isset($skus['100153'])) $skus['100153'] = 0;
        $skus['100153'] += $to_add;
        $temp_slots += $to_add;
      }
      
      // handle amount of rgbw spots
      if ($article->type == 'loxone_lights' && $article->option == 2) {
        $amount_of_rgbw_spots += $article->amount;
      }
      
      // handle amount of ww spots
      if ($article->type == 'loxone_lights' && $article->option == 3) {
        $amount_of_ww_spots += $article->amount;
      }
    }
    
    if ($needs_motion_detector) $amount_of_motion_detectors++;
    if ($has_speaker_in_room) $amount_of_speaker_rooms++;
  }

  if ($needs_weather_station) $skus['100245'] = 1;
  if ($needs_nano_io_air) {
    if (!isset($skus['100153'])) $skus['100153'] = 1;
    $temp_slots++;
  }
  if ($amount_of_motion_detectors > 0) $skus['motion-sensor'] = $amount_of_motion_detectors;
  if ($amount_of_speaker_rooms > 0) {
    if ($amount_of_speaker_rooms <= 4) $skus['100165'] = 1;
    if ($amount_of_speaker_rooms > 4 && $amount_of_speaker_rooms <= 8) $skus['100166'] = 1;
    if ($amount_of_speaker_rooms > 8 && $amount_of_speaker_rooms <= 12) $skus['100167'] = 1;
    if ($amount_of_speaker_rooms > 12 && $amount_of_speaker_rooms <= 16) $skus['100168'] = 1;
    if ($amount_of_speaker_rooms > 16) $skus['100169'] = 1;
    
    $to_add = intdiv($amount_of_speaker_rooms, 12);
    $remainder = $amount_of_speaker_rooms % 12;
    if ($remainder !=0) $to_add++;
    
    if (!isset($skus['200110'])) $skus['200110'] = 0;
    $skus['200110'] = $to_add;
  }
  
  if ($amount_of_rgbw_spots > 0) {
    $to_add = intdiv($amount_of_rgbw_spots, 8);
    $remainder = $amount_of_rgbw_spots % 8;
    if ($remainder !=0) $to_add++;
    
    if (!isset($skus['rgbw-24V-compact-dimmer'])) $skus['rgbw-24V-compact-dimmer'] = 0;
    $skus['rgbw-24V-compact-dimmer'] += $to_add;
    
    if (!isset($skus['200297'])) $skus['200297'] = 0;
    $skus['200297'] += $to_add;
    
    $temp_slots += ($to_add*2);
  }
  
  if ($amount_of_ww_spots > 0) {
    $to_add = intdiv($amount_of_ww_spots, 14);
    $remainder = $amount_of_ww_spots % 14;
    if ($remainder !=0) $to_add++;
    
    if (!isset($skus['rgbw-24V-compact-dimmer'])) $skus['rgbw-24V-compact-dimmer'] = 0;
    $skus['rgbw-24V-compact-dimmer'] += $to_add;
    
    if (!isset($skus['200297'])) $skus['200297'] = 0;
    $skus['200297'] += $to_add;
    
    $temp_slots += ($to_add*2);
  }
  
  // if more than 120 $temp_slots -> add more miniserver-go
  $to_add = intdiv($temp_slots, 120);
  $remainder = $temp_slots % 120;
  if ($remainder !=0) $to_add++;
  if ($to_add > 0) $skus['100139'] = $to_add;
  
  return $skus;
}





add_action( "wp_ajax_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
add_action( "wp_ajax_nopriv_lxhm_calculate_rooms", "lxhm_calculate_rooms" );
?>