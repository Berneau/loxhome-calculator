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

?>