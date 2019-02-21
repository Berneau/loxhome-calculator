<?php
function lxhmGetMiniserverOptions($article) {
  $options = array(
    'jalousie' => array('Verkabelt & Bestehend', 'Loxone Rohrmotor', 'Shading Activator'),
    'fenster' => array('Fensterkontakt Vorhanden', 'Tür-Fensterkontakt Air', 'Fenstergriff Air', 'Loxone Tree Anbindung'),
    'innentuer' => array('Loxone Tree Standard', 'Loxone Tree Exklusive', 'Standard Vorhanden', 'Standard Vorhanden Tree'),
    'raumregelung' => array('Loxone Tree', 'Antrieb Standard vorhanden', 'Loxone Tree Exklusive CO2'),
    'speaker' => array('Standard'),
    'gehaeuse_fuer_betonbau' => array('Standard'),
    'gehaeuse_fuer_trockenbau' => array('Standard'),
    'universalbeleuchtung' => array('Ein/Aus 230V', 'Dimmer 230V', 'Ein/Aus LED 24V', 'Dimmer LED 24V', 'Steckdose und weitere Schaltkontakte'),
    'loxone_lights' => array('Farb Stripe LED', 'Spot RGBW Tree', 'Spot WW', 'LED Pendulum Slim', 'LED Farb Deckenleuchte'),
    'zentral' => array('Zahlencode mit NFC', 'Bewegungsmelder außen', 'Gegensprechanlage')
  );
  return $options[$article];
}

function lxhmGetMiniserverGoOptions($article) {
  $options = array(
    'jalousie' => array('Bestehend mit Schaltdose', 'Bestehend mit Zwischenstecker', 'Loxone Rohrmotor'),
    'fenster' => array('Fensterkontakt Vorhanden', 'Tür-Fensterkontakt Air', 'Fenstergriff Air'),
    'innentuer' => array('Loxone Air Batterie', 'Loxone Air Batterie Deluxe', 'Loxone Air', 'Vorhandener Taster'),
    'raumregelung' => array('Loxone Air Stellantrieb', 'Loxone Air Fühler', 'Loxone Touch Air'),
    'speaker' => array('Standard'),
    'gehaeuse_fuer_betonbau' => array('Standard'),
    'gehaeuse_fuer_trockenbau' => array('Standard'),
    'universalbeleuchtung' => array('Ein/Aus 230V', 'Dimmer 230V', 'Ein/Aus LED 24V', 'Dimmer LED 24V', 'Steckdose'),
    'loxone_lights' => array('Farb Stripe LED', 'Spot RGBW', 'Spot WW', 'LED Pendulum Slim', 'LED Farb Deckenleuchte'),
    'zentral' => array('Zahlencode mit NFC', 'Zahlencode Batterie', 'Gegensprechanlage')
  );
  return $options[$article];
}
?>