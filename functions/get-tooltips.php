<?php
function lxhm_get_tooltips() {
  $serverType = $_POST['serverType'];
  
  if (!$serverType) return;
  
  $tooltips_json = lxhm_read_json_file('area-tooltips');
  $tooltips;
  if ($serverType == 'miniserver') $tooltips = $tooltips_json->miniserver;
  elseif ($serverType == 'miniserver-go') $tooltips = $tooltips_json->miniserver_go;
  
  echo lxhm_create_response(null, $tooltips);
  wp_die();
}
?>