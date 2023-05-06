<?php

/*
 * Application boot
 */

require_once "../app/init.php";

/*
 * Begin rendering output
 */

echo view("main/header", array(
    "config" => $config
));

/*
 * Initilize Data
 */

$forecast = null;
$alerts = null;
$cards = array();

/*
 * Get cached data from the database.  It's faster to grab remote data
 * periodically and cache it in MySQL than it is to request it every time
 * someone tries to load their homepage. If PHP supported multithreading, this
 * wouldn't be as much of an issue.  Doing it this way also takes load off of
 * the remote services.
 */

if ($config["weather_alerts"]["enabled"]) {
    $alerts = US_NWS::retrieveAlertsCache($db, $config);
}

/*
 * In the initial release of Intranet-Home-Page, each of the card types gathered
 * their respective data (if applicable) here.  This was then passed into the
 * body view and injected into the card views.  This design didn't allow a user
 * to have multiple of a single type of card (like an RSS feed) with different
 * data sources.
 * 
 * To fix this and allow for multiple of the same card with separate data
 * sources, we now gather data per card, not per card type.  The data is sent
 * along with the card config (from config.json) to the body view in the $cards
 * array.
 */

foreach ($config["cards"] as $card) {
    switch ($card["type"]) {
	case "us_weather":
	    $card["current"] = US_NWS::retrieveCurrentCache($db, $config);
	    $card["forecast"] = US_NWS::retrieveForecastCache($db, $config);
	    array_push($cards, $card);
	    break;
	case "piwigo":
	    $card["random_image"] = Piwigo::getRandomImage($db);
	    array_push($cards, $card);
	    break;
	case "rss":
	    $card["articles"] = Rss::retrieveRSSCache($db, $card);
	    array_push($cards, $card);
	    break;
	case "caldav":
	    $card["events"] = Caldav::retrieveEvents($db, $card);
	    array_push($cards, $card);
	    break;
	case "local_random_image":
	    $card["random_image"] = Local_Random_Image::getRandomImage();
	    array_push($cards, $card);
	    break;
	default:
	    array_push($cards, $card);
    }
}

echo view("/main/body", array(
    "config" => $config,
    "alerts" => $alerts,
    "cards"  => $cards
));
?>
