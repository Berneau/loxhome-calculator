<?php
include_once LXHM_PLUGIN_DIR . '/includes/article-options.php';

function lxhm_get_options() {
  $serverType = $_POST['serverType'];
  $article = $_POST['article'];
  
  if (!$article) return;
  if (!$serverType) return;
  
  $options;
  if ($serverType == 'miniserver') $options = lxhmGetMiniserverOptions($article);
  elseif ($serverType == 'miniserver-go') $options = lxhmGetMiniserverGoOptions($article);
  
  $html = '<option value="null">Option wählen</option>';

  for ($i = 0; $i < sizeof($options); $i++) {
    $html .= '<option value="';
    $html .= $i+1;
    $html .= '">';
    $html .= $options[$i];
    $html .= '</option>';
  }

  print_r($html);
  wp_die();
}

add_action( "wp_ajax_lxhm_get_options", "lxhm_get_options" );
add_action( "wp_ajax_nopriv_lxhm_get_options", "lxhm_get_options" );
?>