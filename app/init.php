<?php

/*
*   Check if the application is installed already, or if we are installing it now
*/

if (!isset($installed)) {
  $installed = true;
}

/*
*   Get application base path
*/
$base_path = __DIR__;
$path_array = explode("/", $base_path);
$app_path = "";

for ($i = 1; $i < count($path_array) - 1; $i++) {
  $app_path = $app_path . "/" . $path_array[$i];
}

define("APPPATH", $app_path);

//$app_path now contains the absolute path to the installation

/*
*   Get Configuration
*/

if ($installed) {
  $config = file_get_contents("../config.json");
  $config = json_decode($config, true);

  if ($config == null) {
    return "Error reading config file";
  }
}

/*
*   Connect to MySQL
*/

if ($installed) {
  $server = $config["database"]["host"];
  $username = $config["database"]["username"];
  $password = $config["database"]["password"];
  $schema = $config["database"]["schema"];

  try {
    $conn = new PDO("mysql:host=$server;dbname=$schema", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo "Database Connection Failed";
    return;
  }

}

/*
*   The function str_contains was not introduced until PHP8, so we will define
*   it here for those using older versions
*/

if (!function_exists('str_contains')) {
  function str_contains(string $haystack, string $needle): bool
  {
    return '' === $needle || false !== strpos($haystack, $needle);
  }
}

/*
*   Import Classes
*/

//System Classes
require_once $app_path . "/system/view.php";

//Application Classes

if ($installed) {
  require_once $app_path . "/app/classes/us_nws.php";
  require_once $app_path . "/app/classes/piwigo.php";
  require_once $app_path . "/app/classes/rss.php";
  require_once $app_path . "/app/classes/caldav.php";

  $cards = $config["cards"];
} else {
  require_once $app_path . "/app/classes/install.php";
}

?>
