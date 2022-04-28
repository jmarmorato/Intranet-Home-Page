<?php

$installed = false;

require_once "init.php"

$result = Installer::build_database($_SERVER["host"], $_SERVER["user"], $_SERVER["pass"], $_SERVER["schema"], APPPATH);

?>
