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
      try {
        echo "Update NWS Forecast and current conditions" . PHP_EOL;
        US_NWS::updateCache($db, $config, $card);
      } catch (Exception $e) {
        echo "Error updating NWS forecast" . PHP_EOL;
	echo $e;
        return;
      }
    break;
    case "piwigo":
      try {
        echo "Update Piwigo album cache" . PHP_EOL;
        Piwigo::updateCache($db, $card);
      } catch (Exception $e) {
        echo "Error updating Piwigo album";
        return;
      }
      break;
    case "rss":
      try {
        echo "Update RSS Feed Cache" . PHP_EOL;
        Rss::updateCache($db, $card);
      } catch (Exception $e) {
        echo "Error updating RSS feed" . PHP_EOL;
        return;
      }
      break;
    case "caldav":
      try {
      echo "Update CalDAV Events" . PHP_EOL;
      Caldav::updateCache($db, $card);
      } catch (Exception $e) {
	  echo $e;
        echo "Error updating CalDAV events";
        return;
      }
      break;
  }
}

?>
