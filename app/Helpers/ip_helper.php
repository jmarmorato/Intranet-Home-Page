<?php

function get_user_ip(){
  if(!empty($_SERVER['HTTP_CLIENT_IP'])){
    //ip from share internet
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    //ip pass from proxy
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }else{
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

function pick_tenant($tenants, $ip_array) {
  foreach($tenants->getResult() as $row) {
    $subnet_array = explode(".", explode("/", $row->ip_subnet)[0]);
    for ($i = 0; $i < 3; $i++) {
      if ($subnet_array[$i] != $ip_array[$i]) {
        break;
      } else {
        if ($i==3) {
          return $row->tenant_id;
        }
      }
    }
  }
  return 0;
}

?>
