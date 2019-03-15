<?php
class LxhmHouseGo extends LxhmHouse {
  private $serverType;
  private $rooms;
  private $new_and_slots;
  private $ruleset;
  
  function __construct($serverType) {
    $this->new_and_slots = new stdClass();
    $this->rooms = array();

    $this->serverType = $serverType;
    $this->ruleset['needs_weather_station'] = false;
    $this->ruleset['amount_of_motion_detectors'] = 0;
    $this->ruleset['amount_of_speaker_rooms'] = 0;
    $this->ruleset['is_1_selected'] = false;
    $this->ruleset['is_5_selected'] = false;
    $this->ruleset['amount_of_air_sensor_rooms'] = 0;
    $this->ruleset['amount_of_touch_needed'] = 0;
    $this->ruleset['amount_of_touch_pure_needed'] = 0;
    $this->ruleset['nano_io_airs_needed'] = 0;
    $this->ruleset['compact_dimmers_needed'] = 0;
  }
  
  function add_room($room) {
    array_push($this->rooms, $room);
  }
  
  function get_rooms() {
    return $this->rooms;
  }
  
  function calculate() {
    foreach ($this->rooms as $room) {
      $skus = $room->calculate();
      $this->add_to_new_and_slots($skus);
    }
    
    // get extra rules from each room and area
    foreach ($this->rooms as $room) {
      $rules = $room->get_extra_rules();
      $this->combine_rules($rules);
    }
    
    // turn extra rules into additional skus
    $this->interpret_rules();
    
    // check slots needed and add to new
    $this->add_slots_to_news();
    
    // double check if there is at least 1 miniserver go
    $this->double_check();
    
    // return only the skus to add
    return $this->new_and_slots->new;
  }
  
  function double_check() {
    $to_test = $this->new_and_slots->new['100139'];
    if (!isset($to_test)) {
      if ($to_test == 0) {
        $this->new_and_slots->new['100139'] = 1;
      }
    }
  }
  
  function add_slots_to_news() {
    $slots = $this->new_and_slots->slots;

    foreach ($slots as $key => $value) {
      $slots_available = lxhm_get_slots_by_sku($key);
      $amount_to_add = intdiv_and_remainder($slots_available, $value);
      $this->safely_add('new', $key, $amount_to_add);
    }
  }
  
  function add_to_new_and_slots($skus) {
    // add new items to list of skus
    foreach ($skus->new as $key => $value) {
      if (!isset($this->new_and_slots->new[$key])) $this->new_and_slots->new[$key] = 0;
      $this->new_and_slots->new[$key] += $value;
    }
    
    // add new needed slots to list of skus
    foreach ($skus->slots as $key => $value) {
      if (!isset($this->new_and_slots->slots[$key])) $this->new_and_slots->slots[$key] = 0;
      $this->new_and_slots->slots[$key] += $value;
    }
  }
  
  function safely_add($collection, $sku, $amount) {
    if (!isset($this->new_and_slots->$collection[$sku])) $this->new_and_slots->$collection[$sku] = 0;
    $this->new_and_slots->$collection[$sku] += $amount;
  }
  
  function combine_rules($rules) {
    if ($rules['needs_weather_station']) $this->ruleset['needs_weather_station'] = true;
    if ($rules['needs_motion_detector']) $this->ruleset['amount_of_motion_detectors']++;
    if ($rules['has_speaker_in_room']) $this->ruleset['amount_of_speaker_rooms']++;
    if ($rules['is_1_selected']) $this->ruleset['is_1_selected'] = true;
    if ($rules['is_5_selected']) $this->ruleset['is_5_selected'] = true;
    if ($rules['needs_air_sensor']) $this->ruleset['amount_of_air_sensor_rooms']++;
    if ($rules['needs_touch']) $this->ruleset['amount_of_touch_needed']++;
    if ($rules['needs_touch_pure']) $this->ruleset['amount_of_touch_pure_needed']++;
    $this->ruleset['nano_io_airs_needed'] += $rules['nano_io_airs_needed'];
    $this->ruleset['compact_dimmers_needed'] += $rules['compact_dimmers_needed'];
  }
  
  function interpret_rules() {
    $amount_of_motion_detectors = $this->ruleset['amount_of_motion_detectors'];
    $amount_of_speaker_rooms = $this->ruleset['amount_of_speaker_rooms'];
    $amount_of_speakers = $this->new_and_slots->new['200097'];
    $amount_of_all_dis = $this->ruleset['amount_of_all_dis'];
    $amount_of_air_sensor_rooms = $this->ruleset['amount_of_air_sensor_rooms'];
    $amount_of_touch_needed = $this->ruleset['amount_of_touch_needed'];
    $amount_of_touch_pure_needed = $this->ruleset['amount_of_touch_pure_needed'];
    $amount_of_all_nano_ios = $this->ruleset['nano_io_airs_needed'];
    $amount_of_all_compact_dimmers = $this->ruleset['compact_dimmers_needed'];
    
    if ($this->ruleset['needs_weather_station']) {
      $this->safely_add('new', '100245', 1);
      $this->safely_add('slots', '100139', 1);
    }
    
    if ($amount_of_motion_detectors > 0) {
      $this->safely_add('new', '100190', $amount_of_motion_detectors);
      $this->safely_add('slots', '100139', 1);
    }
    
    if ($amount_of_speaker_rooms > 0) {
      if ($amount_of_speaker_rooms <= 4) $this->safely_add('new', '100165', 1);
      if ($amount_of_speaker_rooms > 4 && $amount_of_speaker_rooms <= 8) $this->safely_add('new', '100166', 1);
      if ($amount_of_speaker_rooms > 8 && $amount_of_speaker_rooms <= 12) $this->safely_add('new', '100167', 1);
      if ($amount_of_speaker_rooms > 12 && $amount_of_speaker_rooms <= 16) $this->safely_add('new', '100168', 1);
      if ($amount_of_speaker_rooms > 16) $this->safely_add('new', '100169', 1);
      $this->safely_add('slots', '100139', 1);
    }
    
    if ($amount_of_speakers > 0) {
      $to_add = intdiv_and_remainder(12, $amount_of_speakers);
      $this->safely_add('new', '200110', $to_add);
      $this->safely_add('slots', '100139', $to_add);
    }
    
    if ($amount_of_all_dis > 0) {
      $this->safely_add('new', '100242', $amount_of_all_dis);
    }
 
    if ($amount_of_all_compact_dimmers > 0) {
      $this->safely_add('new', '100324', $amount_of_all_compact_dimmers);
      $this->safely_add('new', '200297', $amount_of_all_compact_dimmers);
      $this->safely_add('slots', '100139', $amount_of_all_compact_dimmers);
    }
    
    if ($amount_of_air_sensor_rooms > 0) {
      $this->safely_add('new', '100264', $amount_of_air_sensor_rooms);
      $this->safely_add('slots', '100139', $amount_of_air_sensor_rooms);
    }
    
    if ($amount_of_touch_needed > 0) {
      $this->safely_add('new', '100155', $amount_of_touch_needed);
      $this->safely_add('slots', '100139', $amount_of_touch_needed);
    }
    
    if ($amount_of_touch_pure_needed > 0) {
      $this->safely_add('new', '100206', $amount_of_touch_pure_needed);
      $this->safely_add('slots', '100139', $amount_of_touch_pure_needed);
    }
    
    // add all nano ios of all rooms
    if ($amount_of_all_nano_ios > 0) {
      $this->safely_add('new', '100153', $amount_of_all_nano_ios);
      $this->safely_add('slots', '100139', $amount_of_all_nano_ios);
    }
  }
}
?>