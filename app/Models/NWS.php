<?php namespace App\Models;

use CodeIgniter\Model;

class NWS extends Model
{
  //This class retrieves alerts, and current and forecast weather conditions
  //from the National Weather Service api

  //The function str_contains was not introduced until PHP8, and the latest PHP
  //8.0 (8.0.13) at this point has an issue with regex that prevents CodeIgnitor
  //from booting correctly.

  //This functions retrieves, deduplicates, and forms a list of active weather
  //alerts in the given alert zone code
  public static function getAlerts($config) {

    if (!function_exists('str_contains')) {
      function str_contains(string $haystack, string $needle): bool
      {
        return '' === $needle || false !== strpos($haystack, $needle);
      }
    }

    // TODO: This url should be generated dynamically at runtime using user
    //preferences stored in the DB / config file.
    $url = $config["weather_alerts"]["url"];

    $curl_obj = curl_init();

    curl_setopt($curl_obj, CURLOPT_URL, $url);
    curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);

    //The National Weather Service API requires a User Agent and email address
    //be passed with each request.
    curl_setopt($curl_obj, CURLOPT_HTTPHEADER, array(
      'User-Agent: (family-intranet, justin@marmorato.net)',
    ));

    $out = curl_exec($curl_obj);

    $alerts = simplexml_load_string($out);

    /*
    * Add alerts to the array if there isn't already an alert of the same type.
    * Sometimes the NWS will issue an alert, and then issue that same alert
    * again for another (overlaping) area, or one that expires later.  Either
    * way, we don't want multiple of the same alerts cluttering the screen.
    */
    $alerts_deduped = array();

    foreach ($alerts as $alert) {

      $alert_title = $alert->title;
      $subject = explode("issued", $alert_title);
      $dedupe_contains = false;

      foreach($alerts_deduped as $deduped_alert) {

        $deduped_alert_title = $deduped_alert->title;
        if (str_contains($deduped_alert_title, $subject[0])) {
          $dedupe_contains = true;
        }
      }
      if (!$dedupe_contains) {
        array_push($alerts_deduped, $alert);
      }
    }

    return $alerts_deduped;
  }

  public static function getForecast() {
    $url = "https://api.weather.gov/gridpoints/PHI/51,101/forecast";

    $output = array();


    $curl_obj = curl_init();

    curl_setopt($curl_obj, CURLOPT_URL, $url);
    curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_obj, CURLOPT_HTTPHEADER, array(
      'User-Agent: (family-intranet, justin@marmorato.net)',
      //'Accept: application/cap+xml'
    ));

    $out = curl_exec($curl_obj);

    if (isset(json_decode($out, true)["properties"]["periods"])) {
      $forecast = json_decode($out, true)["properties"]["periods"];
    } else {
      $forecast = null;
    }

    if (isset(json_decode($out, true)["properties"]) &&
    isset(json_decode($out, true)["properties"]["periods"])) {

      $forecast = json_decode($out, true)["properties"]["periods"];

      for($i = 0; $i < 5; $i++) {

        array_push($output, array(
          "id"          => $forecast[$i]["number"],
          "name"        => $forecast[$i]["name"],
          "icon"        => $forecast[$i]["icon"],
          "is_daytime"  => $forecast[$i]["isDaytime"],
          "temperature" => $forecast[$i]["temperature"],
          "forecast"    => $forecast[$i]["shortForecast"],
          "detailed"    => $forecast[$i]["detailedForecast"]
        ));

      }

    } else {
      $output["error"] = "wx_err";
    }

    //Uncomment the below line to test what happens if NWS API call doesn't
    //return "periods" array.

    //$output["error"] = "wx_err";

    return $output;

  }

  public static function getCurrentConditions() {
    $url = "https://api.weather.gov//stations/KDYL/observations/latest";

    $curl_obj = curl_init();

    curl_setopt($curl_obj, CURLOPT_URL, $url);
    curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_obj, CURLOPT_HTTPHEADER, array(
      'User-Agent: (family-intranet, justin@marmorato.net)',
      //'Accept: application/cap+xml'
    ));

    $out = curl_exec($curl_obj);
    $conds = json_decode($out, true)["properties"];

    return $conds;

  }

}
