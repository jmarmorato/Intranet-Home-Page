<?php

/*
*   Application boot
*/

require_once "init.php";

/*
*   Run through each card and cache external data
*/

foreach ($cards as $card) {
  switch ($card["type"]) {
    case "us_weather":
      echo "Update NWS Forecast and current conditions" . PHP_EOL;
      US_NWS::updateCache($conn, $config);
    break;
    case "piwigo":
      echo "Update Piwigo album cache" . PHP_EOL;
      Piwigo::updateCache($conn, $card);
      break;
    case "rss":
      echo "Update RSS Feed Cache" . PHP_EOL;
      Rss::updateCache($conn, $card);
      break;
    case "caldav":
      echo "Update CalDAV Events" . PHP_EOL;
      Caldav::updateCache($conn, $card);
      break;
  }
}

?>
