<?php
function lxhm_add_article() {
  echo file_get_contents(LXHM_PLUGIN_DIR . '/templates/article.template.php');
  wp_die();
}

add_action( "wp_ajax_lxhm_add_article", "lxhm_add_article" );
add_action( "wp_ajax_nopriv_lxhm_add_article", "lxhm_add_article" );
?>