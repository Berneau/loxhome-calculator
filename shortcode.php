<?php
function lxhm_init_shortcode() {
  ob_start();
  include_once LXHM_PLUGIN_DIR . '/templates/form.template.php';
  return ob_get_clean();
}

add_shortcode('lxhm', 'lxhm_init_shortcode');
?>