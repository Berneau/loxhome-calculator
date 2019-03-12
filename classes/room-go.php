<?php
class LxhmRoomGo {
  private $areas;
  private $name;
  private $new_and_slots;
  private $ruleset;
  
  function __construct($name) {
    $this->name = $name;
    $this->ruleset['needs_weather_station'] = false;
    $this->ruleset['needs_motion_detector'] = false;
    $this->ruleset['has_speaker_in_room'] = false;
    $this->ruleset['is_1_selected'] = false;
    $this->ruleset['is_5_selected'] = false;
    $this->ruleset['amount_of_230V_lights'] = 0;
    $this->ruleset['amount_of_24V_lights'] = 0;
    $this->ruleset['amount_of_dimmer_lights'] = 0;
    $this->ruleset['amount_of_rgbw_spots'] = 0;
    $this->ruleset['amount_of_ww_spots'] = 0;
    $this->ruleset['amount_of_pendulums'] = 0;
    $this->ruleset['needs_air_sensor'] = false;
    $this->ruleset['is_9_selected'] = false;
    $this->ruleset['is_10_selected'] = false;
    $this->ruleset['is_15_selected'] = false;
    $this->ruleset['is_16_selected'] = false;
    $this->ruleset['needs_touch'] = false;
    $this->ruleset['needs_touch_pure'] = false;
    $this->ruleset['nano_io_airs_needed'] = 0;
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
    if ($rules['is_1_selected']) $this->ruleset['is_1_selected'] = true;
    if ($rules['is_5_selected']) $this->ruleset['is_5_selected'] = true;
    $this->ruleset['amount_of_230V_lights'] += $rules['amount_of_230V_lights'];
    $this->ruleset['amount_of_24V_lights'] += $rules['amount_of_24V_lights'];
    $this->ruleset['amount_of_dimmer_lights'] += $rules['amount_of_dimmer_lights'];
    $this->ruleset['amount_of_rgbw_spots'] += $rules['amount_of_rgbw_spots'];
    $this->ruleset['amount_of_ww_spots'] += $rules['amount_of_ww_spots'];
    $this->ruleset['amount_of_pendulums'] += $rules['amount_of_pendulums'];
    if ($rules['needs_air_sensor']) $this->ruleset['needs_air_sensor'] = true;
    if ($rules['is_15_selected']) $this->ruleset['is_15_selected'] = true;
    if ($rules['is_16_selected']) $this->ruleset['is_16_selected'] = true;
    if ($rules['is_9_selected']) $this->ruleset['is_9_selected'] = true;
    if ($rules['is_10_selected']) $this->ruleset['is_10_selected'] = true;
    $this->ruleset['amount_of_zahlencodes'] += $rules['amount_of_zahlencodes'];
    $this->ruleset['nano_io_airs_needed'] += $rules['nano_io_airs_needed'];
  }
  
  function interpret_rules() {
    if ($this->ruleset['is_15_selected']) {
      if (!$this->ruleset['is_9_selected']) {
        $this->ruleset['needs_touch'] = true;
      }
    }
    
    if ($this->ruleset['is_16_selected']) {
      if (!$this->ruleset['is_10_selected']) {
        $this->ruleset['needs_touch_pure'] = true;
      }
    }
  }
}
?>