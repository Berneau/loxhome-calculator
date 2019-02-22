<?php
function lxhm_add_room() {
  $template = lxhm_parse_template('room');
  echo lxhm_create_response($template);
  wp_die();
}

function lxhm_add_area() {
  $template = lxhm_parse_template('area');
  echo lxhm_create_response($template);
  wp_die();
}

// reads template file and returns html
function lxhm_parse_template($template_name) {
  ob_start();
  include_once LXHM_PLUGIN_DIR . '/templates/' . $template_name . '.template.php';
  return ob_get_clean();
}
?>