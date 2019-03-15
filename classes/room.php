<?php
class LxhmRoom {
  private $areas;
  private $name;
  private $new_and_slots;
  private $ruleset;
  
  function __construct($name) {
    $this->name = $name;
    $this->ruleset['needs_weather_station'] = false;
    $this->ruleset['needs_motion_detector'] = false;
    $this->ruleset['has_speaker_in_room'] = false;
    $this->ruleset['is_10_selected'] = false;
    $this->ruleset['is_11_selected'] = false;
    $this->ruleset['is_14_or_15_selected'] = false;
    $this->ruleset['is_16_selected'] = false;
    $this->ruleset['needs_touch'] = false;
    $this->ruleset['needs_touch_pure'] = false;
    $this->ruleset['amount_of_di_slots_per_room'] = 0;
    $this->ruleset['amount_of_dis_per_room'] = 0;
    $this->ruleset['amount_of_rgbw_spots'] = 0;
    $this->ruleset['amount_of_ww_spots'] = 0;
    $this->ruleset['amount_of_pendulums'] = 0;
    $this->ruleset['amount_of_ceiling_lights'] = 0;
    $this->ruleset['amount_of_dimmer_leds'] = 0;
    $this->ruleset['needs_room_sensor'] = false;
  }
  
  function add_area($area) {
    if (!isset($this->areas)) $this->areas = array();
    array_push($this->areas, $area);
  }
  
  function calculate() {
    foreach ($this->areas as $area) {
      $skus = $area->calculate();
      $this->add_to_new_and_slots($skus);
    }

    return $this->new_and_slots;
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
  
  function get_extra_rules() {
    foreach ($this->areas as $area) {
      $this->combine_rules($area->get_extra_rules());
    }
    
    $this->interpret_rules();
    
    return $this->ruleset;
  }
  
  function combine_rules($rules) {
    if ($rules['needs_weather_station']) $this->ruleset['needs_weather_station'] = true;
    if ($rules['needs_motion_detector']) $this->ruleset['needs_motion_detector'] = true;
    if ($rules['has_speaker_in_room']) $this->ruleset['has_speaker_in_room'] = true;
    if ($rules['is_10_selected']) $this->ruleset['is_10_selected'] = true;
    if ($rules['is_11_selected']) $this->ruleset['is_11_selected'] = true;
    if ($rules['is_14_or_15_selected']) $this->ruleset['is_14_or_15_selected'] = true;
    if ($rules['is_16_selected']) $this->ruleset['is_16_selected'] = true;
    $this->ruleset['amount_of_di_slots_per_room'] += $rules['amount_of_di_slots'];
    $this->ruleset['amount_of_rgbw_spots'] += $rules['amount_of_rgbw_spots'];
    $this->ruleset['amount_of_ww_spots'] += $rules['amount_of_ww_spots'];
    $this->ruleset['amount_of_pendulums'] += $rules['amount_of_pendulums'];
    $this->ruleset['amount_of_ceiling_lights'] += $rules['amount_of_ceiling_lights'];
    $this->ruleset['amount_of_dimmer_leds'] += $rules['amount_of_dimmer_leds'];
    if ($rules['needs_room_sensor']) $this->ruleset['needs_room_sensor'] = true;
  }
  
  function interpret_rules() {
    
    if ($this->ruleset['is_14_or_15_selected']) {
      if (!$this->ruleset['is_10_selected']) {
        $this->ruleset['needs_touch'] = true;
      }
    }
    
    if ($this->ruleset['is_16_selected']) {
      if (!$this->ruleset['is_11_selected']) {
        $this->ruleset['needs_touch_pure'] = true;
      }
    }
    
    $amount_of_di_slots = $this->ruleset['amount_of_di_slots_per_room'];
    if ($amount_of_di_slots > 0) {
      $amount_of_dis_per_room = intdiv_and_remainder(6, $amount_of_di_slots);
      $this->ruleset['amount_of_dis_per_room'] = $amount_of_dis_per_room;
    }
  }
}
?>