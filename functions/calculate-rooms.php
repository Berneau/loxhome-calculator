<?php
function lxhm_calculate_rooms() {
  
  $decodedData = lxhm_decode_data($_POST['formData']);
  if (!$decodedData) return;
  
  $house = lxhm_initialize_house($decodedData);
  
  $skus = $house->calculate();
  
  $response = lxhm_get_html_from_skus($skus);
  
  echo lxhm_create_response($response->html, $response->products);
  wp_die();
}


function lxhm_initialize_house($data) {
  
  $miniserver = false;
  if ($data->serverType == 'miniserver') $miniserver = true;
  
  // read skus from json file at central point
  $skus_from_json = lxhm_read_json_file('sku-' . $data->serverType);
  
  // var_dump($skus_from_json);
  
  // initialize house
  $house;
  if ($miniserver) $house = new LxhmHouse($data->serverType);
  elseif (!$miniserver) $house = new LxhmHouseGo($data->serverType);
  
  // add rooms to house
  foreach ($data->rooms as $room_elem) {
    
    // init room
    $room;
    if ($miniserver) $room = new LxhmRoom($room_elem->roomName);
    elseif (!$miniserver) $room = new LxhmRoomGo($room_elem->roomName);
    
    // add areas to room
    foreach ($room_elem->articles as $area_elem) {
      
      // init area
      $area;
      if ($miniserver) $area = new LxhmArea($area_elem->type, $area_elem->amount, $area_elem->option, $skus_from_json);
      elseif (!$miniserver) $area = new LxhmAreaGo($area_elem->type, $area_elem->amount, $area_elem->option, $skus_from_json);
      
      // add area to room
      $room->add_area($area);
    }
  
    // add room to $house
    $house->add_room($room);
  }

  return $house;
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

?>