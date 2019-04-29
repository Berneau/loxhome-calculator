<?php
function lxhm_get_options() {
  $serverType = $_POST['serverType'];
  $article = $_POST['article'];
  
  if (!$article) return;
  if (!$serverType) return;
  
  $options_json = lxhm_read_json_file('area-options');
  $options;
  if ($serverType == 'miniserver') $options = $options_json->miniserver->$article;
  elseif ($serverType == 'miniserver-go') $options = $options_json->miniserver_go->$article;
  
  $tooltips_json = lxhm_read_json_file('area-tooltips');
  $tooltips;
  if ($serverType == 'miniserver') $tooltips = $tooltips_json->miniserver->$article;
  elseif ($serverType == 'miniserver-go') $tooltips = $tooltips_json->miniserver_go->$article;
  
  $html = '<option value="null" disabled selected>Option wählen</option>';
  for ($i = 0; $i < sizeof($options); $i++) {
    $html .= '<option value="';
    $html .= $i+1;
    $html .= '">';
    $html .= $options[$i];
    $html .= '</option>';
  }
  
  echo lxhm_create_response($html, $tooltips);
  wp_die();
}
?>