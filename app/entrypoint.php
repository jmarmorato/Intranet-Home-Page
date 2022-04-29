<?php

$installed = false;

require_once "init.php";

$result = Installer::build_database($_SERVER["DB_HOST"], $_SERVER["DB_USER"], $_SERVER["DB_PASS"], $_SERVER["DB_SCHEMA"], APPPATH);

?>
