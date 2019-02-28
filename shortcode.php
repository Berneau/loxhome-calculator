<?php
function lxhm_init_shortcode() {
  return lxhm_parse_template('form');
}

add_shortcode('lxhm', 'lxhm_init_shortcode');
?>