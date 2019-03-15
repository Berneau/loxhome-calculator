<?php
class LxhmAreaGo {
  private $name;
  private $amount;
  private $option;
  private $skus_from_file;
  private $new_and_slots;
  private $ruleset;
  
  function __construct($name, $amount, $option, $skus_from_file) {
    $this->name = $name;
    $this->amount = (int)$amount;
    $this->option = $option;
    $this->skus_from_file = $skus_from_file;
    $this->ruleset['needs_weather_station'] = false;
    $this->ruleset['needs_motion_detector'] = false;
    $this->ruleset['has_speaker_in_room'] = false;
    $this->ruleset['is_1_selected'] = false;
    $this->ruleset['is_5_selected'] = false;
    $this->ruleset['amount_of_on_off_24v'] = 0;
    $this->ruleset['amount_of_dimmer_24v'] = 0;
    $this->ruleset['amount_of_rgbw_spots'] = 0;
    $this->ruleset['amount_of_ww_spots'] = 0;
    $this->ruleset['amount_of_pendulums'] = 0;
    $this->ruleset['needs_air_sensor'] = false;
    $this->ruleset['is_9_selected'] = false;
    $this->ruleset['is_10_selected'] = false;
    $this->ruleset['is_15_selected'] = false;
    $this->ruleset['is_16_selected'] = false;
    $this->ruleset['nano_io_slots_needed'] = 0;
  }
  
  function calculate() {
  
    // get skus from list
    $skus = $this->lxhm_request_skus();
  
    // add skus to list
    $this->add_to_new_and_slots($skus);
  
    // calculates now for later request
    $this->handle_extra_rules();
  
    return $this->new_and_slots;
  }
  
  function lxhm_request_skus() {
    $name = $this->name;
    $option = $this->option;
    return $this->skus_from_file->$name->$option;
  }
  
  function add_to_new_and_slots($skus) {    
    for ($i=0; $i < sizeof($skus); $i++) {
      
      // add new items to list of skus
      if (!isset($this->new_and_slots->new[$skus[$i]->sku])) $this->new_and_slots->new[$skus[$i]->sku] = 0;
      $this->new_and_slots->new[$skus[$i]->sku] += ($skus[$i]->amount) * $this->amount;
      
      // add new needed slots to list of skus
      if (isset($skus[$i]->slots)) {
        if (!isset($this->new_and_slots->slots['100139'])) $this->new_and_slots->slots['100139'] = 0;
        $this->new_and_slots->slots['100139'] += $skus[$i]->slots;
      }
    }
  }
  
  function handle_extra_rules() {
    if ($this->name == 'jalousie') {
      $this->ruleset['needs_weather_station'] = true;
      if ($this->option == 1) $this->ruleset['is_1_selected'] = true;
      if ($this->option == 1) $this->ruleset['nano_io_slots_needed'] += $this->amount;
    }
    
    if ($this->name == 'fenster') {
      if ($this->option == 1) $this->ruleset['is_5_selected'] = true;
    }
    
    if ($this->name == 'innentuer') {
      if ($this->option == 1) $this->ruleset['is_9_selected'] = true;
      if ($this->option == 2) $this->ruleset['is_10_selected'] = true;
      if ($this->option == 5) $this->ruleset['nano_io_slots_needed'] += $this->amount;
    }
    
    if ($this->name == 'raumregelung') {
      $this->ruleset['needs_motion_detector'] = true;
      if ($this->option == 2) $this->ruleset['needs_air_sensor'] = true;
      if ($this->option == 3) $this->ruleset['is_15_selected'] = true;
      if ($this->option == 3) $this->ruleset['nano_io_slots_needed'] += $this->amount;
      if ($this->option == 4) $this->ruleset['is_16_selected'] = true;
      if ($this->option == 4) $this->ruleset['nano_io_slots_needed'] += $this->amount;
    }
    
    if ($this->name == 'speaker') {
      $this->ruleset['needs_motion_detector'] = true;
      $this->ruleset['has_speaker_in_room'] = true;
    }
    
    if ($this->name == 'universalbeleuchtung') {
      $this->ruleset['needs_motion_detector'] = true;
      if ($this->option == 1) $this->ruleset['nano_io_slots_needed'] += $this->amount;
      if ($this->option == 3) $this->ruleset['amount_of_on_off_24v'] = $this->amount;
      if ($this->option == 4) $this->ruleset['amount_of_dimmer_24v'] = $this->amount;
    }
    
    if ($this->name == 'loxone_lights') {
      $this->ruleset['needs_motion_detector'] = true;
      if ($this->option == 2) $this->ruleset['amount_of_rgbw_spots'] = $this->amount;
      if ($this->option == 3) $this->ruleset['amount_of_ww_spots'] = $this->amount;
      if ($this->option == 4) $this->ruleset['amount_of_pendulums'] = $this->amount;
    }
    
    if ($this->name == 'zentral') {
      if ($this->option == 1) $this->ruleset['nano_io_slots_needed'] += $this->amount;
    }
  }
  
  function get_extra_rules() {
    return $this->ruleset;
  }
}
?>