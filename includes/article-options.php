<?php
function lxhmGetArticleOptions($article) {
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
?>