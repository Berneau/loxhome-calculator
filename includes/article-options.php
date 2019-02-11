<?php
function lxhmGetMiniserverOptions($article) {
  $options = array(
    'jalousie' => array('Verkabelt & Bestehend', 'Rohrmotor', 'Shading Activator'),
    'fenster' => array('Vorhandener Fensterkontakt', 'Türfensterkontkat Air', 'Fenstergriff Air', 'Tree verkabelt'),
    'innentuer' => array('Loxone Tree', 'Verkabelt vorhanden', 'Verkabelt vorhanden Tree'),
    'raumregelung' => array('Loxone Tree', 'Verkabelt vorhanden', 'Loxone Tree CO2'),
    'speaker' => array('Keine Optionen Verfügbar'),
    'gehaeuse_fuer_betonbau' => array('Keine Optionen Verfügbar'),
    'gehaeuse_fuer_trockenbau' => array('Keine Optionen Verfügbar'),
    'universalbeleuchtung' => array('Ein/Aus 230V', 'Dimmer 230V', 'Ein/Aus LED 24V', 'Dimmer LED 24V', 'Steckdose und weitere Schaltkontakte'),
    'loxone_lights' => array('Farb LED 24V', 'Spot RGBW Tree', 'Spot WW', 'LED Pendulum Slim', 'LED Farb Deckenleuchte'),
    'zentral' => array('Zahlencode mit NFC', 'Bewegungsmelder außen', 'Gegensprechanlage')
  );
  return $options[$article];
}

function lxhmGetMiniserverGoOptions($article) {
  $options = array(
    'jalousie' => array('Bestehend mit Schaltdose', 'Bestehend mit Zwischenstecker', 'Rohrmotor'),
    'fenster' => array('Vorhandener Fensterkontakt', 'Türfensterkontakt Air', 'Fenstergriff Air'),
    'innentuer' => array('Loxone Air Batterie', 'Loxone Air', 'Vorhandener Taster'),
    'raumregelung' => array('Loxone Air Stellantrieb', 'Loxone Air Fühler', 'Loxone Touch Air'),
    'speaker' => array('Keine Optionen Verfügbar'),
    'gehaeuse_fuer_betonbau' => array('Keine Optionen Verfügbar'),
    'gehaeuse_fuer_trockenbau' => array('Keine Optionen Verfügbar'),
    'universalbeleuchtung' => array('Ein/Aus 230V', 'Dimmer 230V', 'Ein/Aus LED 24V', 'Dimmer LED 24V', 'Steckdose'),
    'loxone_lights' => array('Farb LED 24V', 'Spot RGBW', 'Spot WW', 'LED Pendulum Slim', 'LED Farb Deckenleuchte'),
    'zentral' => array('Zahlencode mit NFC', 'Zahlencode Batterie', 'Gegensprechanlage')
  );
  return $options[$article];
}
?>