<?php

/*
*   Check if the application is installed already, or if we are installing it now
*/

if (!isset($installed)) {
  $installed = true;
}

/*
*   Check if the user requested the page to be rendered in dark mode
*/

$dark_mode = false;

if (isset($_GET["style"]) && $_GET["style"] == "dark") {
  $dark_mode = true;
}

define("DARKMODE", $dark_mode);



/*
*   Get application base path, and define constant APPPATH to point to the
*   absolute path to the installation.
*/
$base_path = __DIR__;
$path_array = explode("/", $base_path);
$app_path = "";

for ($i = 1; $i < count($path_array) - 1; $i++) {
  $app_path = $app_path . "/" . $path_array[$i];
}

define("APPPATH", $app_path);

/*
*   Get Configuration
*/

if ($installed) {
  $config = file_get_contents(APPPATH . "/config/config.json");
  $config = json_decode($config, true);

  if ($config == null) {
    echo "Error reading config file";
    return;
  }
}

/*
*   Connect to MySQL
*/

$db_config_path = APPPATH . "/writable/db.json";
$db_config = json_decode(file_get_contents($db_config_path));

if ($installed) {
  $server = $db_config->host;
  $username = $db_config->user;
  $password = $db_config->pass;
  $schema = $db_config->db;

  try {
    $db = new PDO("mysql:host=$server;dbname=$schema", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo "Database Connection Failed\r";
    echo "If this is the first time you are running the software, make sure you have run install.php";
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
require_once $app_path . "/app/darkmode.php";

//Application Classes

if ($installed) {
  require_once $app_path . "/app/classes/us_nws.php";
  require_once $app_path . "/app/classes/piwigo.php";
  require_once $app_path . "/app/classes/rss.php";
  require_once $app_path . "/app/classes/caldav.php";
  require_once $app_path . "/app/classes/local_rand_image.php";

  $cards = $config["cards"];
} else {
  require_once $app_path . "/app/classes/install.php";
}

?>
