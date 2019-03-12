<?php
function lxhm_get_options() {
  $serverType = $_POST['serverType'];
  $article = $_POST['article'];
  
  if (!$article) return;
  if (!$serverType) return;
  
  $json_from_file = lxhm_read_json_file('area-options');
  
  $options;
  if ($serverType == 'miniserver') $options = $json_from_file->miniserver->$article;
  elseif ($serverType == 'miniserver-go') $options = $json_from_file->miniserver_go->$article;
  
  $html = '<option value="null" disabled selected>Option wählen</option>';
  for ($i = 0; $i < sizeof($options); $i++) {
    $html .= '<option value="';
    $html .= $i+1;
    $html .= '">';
    $html .= $options[$i];
    $html .= '</option>';
  }

  echo lxhm_create_response($html);
  wp_die();
}
?>