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
    
    return $this->ruleset;
  }
  
  function combine_rules($rules) {
    if ($rules['needs_weather_station']) $this->ruleset['needs_weather_station'] = true;
    if ($rules['needs_motion_detector']) $this->ruleset['needs_motion_detector'] = true;
    if ($rules['has_speaker_in_room']) $this->ruleset['has_speaker_in_room'] = true;
    if ($rules['is_1_selected']) $this->ruleset['is_1_selected'] = true;
    if ($rules['is_5_selected']) $this->ruleset['is_5_selected'] = true;
    if ($rules['is_5_selected']) $this->ruleset['is_5_selected'] = true;
    $this->ruleset['amount_of_230V_lights'] += $rules['amount_of_230V_lights'];
  }
}
?>