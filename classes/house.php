<?php
class LxhmHouse {
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
    $this->ruleset['is_10_selected'] = false;
    $this->ruleset['is_11_selected'] = false;
    $this->ruleset['is_14_or_15_selected'] = false;
    $this->ruleset['is_16_selected'] = false;
    $this->ruleset['amount_of_rgbw_spots'] = 0;
    $this->ruleset['amount_of_ww_spots'] = 0;
    $this->ruleset['amount_of_pendulums'] = 0;
    $this->ruleset['amount_of_ceiling_lights'] = 0;
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
    
    // add netzteile regarding to new_and_slots
    $this->add_netzteile();
    
    // return only the skus to add
    return $this->new_and_slots->new;
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
    if ($collection == 'new') {
      if (!isset($this->new_and_slots->new[$sku])) $this->new_and_slots->new[$sku] = 0;
      $this->new_and_slots->new[$sku] += $amount;
    }
    elseif ($collection == 'slots') {
      if (!isset($this->new_and_slots->slots[$sku])) $this->new_and_slots->slots[$sku] = 0;
      $this->new_and_slots->slots[$sku] += $amount;
    }
  }
  
  function combine_rules($rules) {
    if ($rules['needs_weather_station']) $this->ruleset['needs_weather_station'] = true;
    if ($rules['needs_motion_detector']) $this->ruleset['amount_of_motion_detectors']++;
    if ($rules['has_speaker_in_room']) $this->ruleset['amount_of_speaker_rooms']++;
    if ($rules['is_10_selected']) $this->ruleset['is_10_selected'] = true;
    if ($rules['is_11_selected']) $this->ruleset['is_11_selected'] = true;
    if ($rules['is_14_or_15_selected']) $this->ruleset['is_14_or_15_selected'] = true;
    if ($rules['is_16_selected']) $this->ruleset['is_16_selected'] = true;
    $this->ruleset['amount_of_all_dis'] += $rules['amount_of_dis_per_room'];
    $this->ruleset['amount_of_rgbw_spots'] += $rules['amount_of_rgbw_spots'];
    $this->ruleset['amount_of_ww_spots'] += $rules['amount_of_ww_spots'];
    $this->ruleset['amount_of_pendulums'] += $rules['amount_of_pendulums'];
    $this->ruleset['amount_of_ceiling_lights'] += $rules['amount_of_ceiling_lights'];
  }
  
  function interpret_rules() {
    $amount_of_motion_detectors = $this->ruleset['amount_of_motion_detectors'];
    $amount_of_speaker_rooms = $this->ruleset['amount_of_speaker_rooms'];
    $amount_of_ww_spots = $this->new_and_slots->new['100203'];
    $amount_of_all_dis = $this->ruleset['amount_of_all_dis'];
    $amount_of_all_rgbw_spots = $this->ruleset['amount_of_rgbw_spots'];
    $amount_of_all_ww_spots = $this->ruleset['amount_of_ww_spots'];
    $amount_of_all_pendulums = $this->ruleset['amount_of_pendulums'];
    $amount_of_all_ceiling_lights = $this->ruleset['amount_of_ceiling_lights'];
    
    if ($this->ruleset['needs_weather_station']) {
      $this->safely_add('new', '100246', 1);
      $this->safely_add('slots', '100218', 1);
    }
    
    if ($amount_of_motion_detectors > 0) {
      $this->safely_add('new', '100223', $amount_of_motion_detectors);
    }
    
    if ($amount_of_speaker_rooms > 0) {
      if ($amount_of_speaker_rooms <= 4) $this->safely_add('new', '100165', 1);
      if ($amount_of_speaker_rooms > 4 && $amount_of_speaker_rooms <= 8) $this->safely_add('new', '100166', 1);
      if ($amount_of_speaker_rooms > 8 && $amount_of_speaker_rooms <= 12) $this->safely_add('new', '100167', 1);
      if ($amount_of_speaker_rooms > 12 && $amount_of_speaker_rooms <= 16) $this->safely_add('new', '100168', 1);
      if ($amount_of_speaker_rooms > 16) $this->safely_add('new', '100169', 1);
      $this->safely_add('slots', '100218', $amount_of_speaker_rooms);
    }
    
    if ($this->ruleset['is_14_or_15_selected']) {
      if (!$this->ruleset['is_10_selected']) {
        $this->safely_add('new', '100221', 1);
        $this->safely_add('slots', '100218', 1);
      }
    }
    
    if ($this->ruleset['is_16_selected']) {
      if (!$this->ruleset['is_11_selected']) {
        $this->safely_add('new', '100219', 1);
        $this->safely_add('slots', '100218', 1);
      }
    }
    
    if ($amount_of_ww_spots > 0) {
      $amount_of_dimmer = intdiv_and_remainder(10, $amount_of_ww_spots);
      $amount_of_exts = intdiv_and_remainder(4, $amount_of_dimmer);
      $this->safely_add('slots', '100239', $amount_of_dimmer);
      $this->safely_add('slots', '100218', $amount_of_exts);
    }
    
    if ($amount_of_all_dis > 0) $this->safely_add('new', '100242', $amount_of_all_dis);
    
    if ($amount_of_all_rgbw_spots > 0) {
      $amount_to_add = intdiv_and_remainder(8, $amount_of_all_rgbw_spots);
      $this->safely_add('new', '200002', $amount_to_add);
    }
    
    if ($amount_of_ww_spots > 0) {
      $amount_to_add = intdiv_and_remainder(40, $amount_of_ww_spots);
      $this->safely_add('new', '200002', $amount_to_add);
    }
    
    if ($amount_of_all_pendulums > 0) {
      $amount_to_add = intdiv_and_remainder(5, $amount_of_all_pendulums);
      $this->safely_add('new', '200002', $amount_to_add);
    }
    
    if ($amount_of_all_ceiling_lights > 0) {
      $amount_to_add = intdiv_and_remainder(3, $amount_of_all_ceiling_lights);
      $this->safely_add('new', '200002', $amount_to_add);
    }
    
    // add miniserver last
    $this->safely_add('new', '100001', 1);
  }
  
  function add_netzteile() {
    // add 4,2A netzteil per tree extension
    $amount_of_tree_extensions = $this->new_and_slots->new['100218'];
    if ($amount_of_tree_extensions > 0) $this->safely_add('new', '200002', $amount_of_tree_extensions);
    
    // add 4,2A netzteil if at least 1 extension -> else only 1,3A netzteil
    $at_least_one_extension = false;
    $extensions_to_check = array('100218', '100029', '100038' , '100002', '100114', '100116', '100283');
    
    for ($i = 0; $i < sizeof($extensions_to_check); $i++) {
      if ($this->new_and_slots->new[$extensions_to_check[$i]]) {
        $at_least_one_extension = true;
        break;
      }
    }
    
    if ($at_least_one_extension) $this->safely_add('new', '200002', 1);
    else $this->safely_add('new', '200001', 1);
  }
}
?>