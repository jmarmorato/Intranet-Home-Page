<?php

/*
*   Application boot
*/

require_once "../app/init.php";

/*
*   Begin rendering output
*/

echo view("main/header", array(
  "config" => $config
));

/*
*   Initilize Data
*/

$forecast = null;
$alerts = null;

//Get cached data from database

if($config["weather_alerts"]["enabled"]) {
  $alerts = US_NWS::retrieveAlertsCache($conn, $config);
}

foreach ($config["cards"] as $card) {
  switch ($card["type"]) {
    case "us_weather":
      $current = US_NWS::retrieveCurrentCache($conn, $config);
      $forecast = US_NWS::retrieveForecastCache($conn, $config);
      break;
    case "piwigo":
      $random_image = Piwigo::getRandomImage($conn);
      break;
    case "rss":
      $articles = Rss::retrieveRSSCache($conn, $card);
      break;
    case "caldav":
      $events = Caldav::retrieveEvents($conn, $card);
      break;
    case "local_random_image":
      $random_image = Local_Random_Image::getRandomImage();
  }
}

echo view("/main/body", array(
  "config" => $config,
  "alerts" => $alerts,
  "forecast" => $forecast,
  "current" => $current,
  "random_image" => $random_image,
  "articles" => $articles,
  "events" => $events,
));

?>
