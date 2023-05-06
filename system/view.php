<?php

function view($rel_path, $data = array()) {
  //Start output buffer
  ob_start();

  include APPPATH . "/app/views/" . $rel_path . ".php";
  //Save output buffer to variable
  $output = ob_get_contents();

  //Clear output buffer
  ob_end_clean();

  //Return output buffer so it can be placed
  return $output;

}

 ?>
