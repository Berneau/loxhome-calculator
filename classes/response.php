<?php
class LxhmResponse {
  
  function __construct($html, $data) {
    $this->html = $html;
    $this->data = $data;
  }
  
  function expose() {
    return get_object_vars($this);
  }
  
  function get_json() {
    return json_encode($this->expose());
  }
}


?>