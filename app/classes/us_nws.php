<?php

class US_NWS
{
  //This class retrieves alerts, and current and forecast weather conditions
  //from the National Weather Service api

  //This functions retrieves, deduplicates, and forms a list of active weather
  //alerts in the given alert zone code
  public static function getAlerts($config) {

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

    return $output;

  }

  public static function getCurrentConditions() {
    $url = "https://api.weather.gov//stations/KDYL/observations/latest";

    $curl_obj = curl_init();

    curl_setopt($curl_obj, CURLOPT_URL, $url);
    curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_obj, CURLOPT_HTTPHEADER, array(
      'User-Agent: (family-intranet, justin@marmorato.net)',
    ));

    $out = curl_exec($curl_obj);
    $conds = json_decode($out, true)["properties"];

    return $conds;

  }

  public static function updateCache($db, $config) {

    $forecast = null;

    //Sometimes the forecast API call fails, so repeat until the result isn't null
    while (is_null($forecast)) {
      $forecast = self::getForecast();
    }

    $db->beginTransaction();
    $sql = "INSERT INTO nws_forecast(`name`, `icon`, `is_daytime`, `temperature`, `forecast`, `detailed`) VALUES (:name, :icon, :is_daytime, :temperature, :forecast, :detailed);";
    $db->query("DELETE FROM nws_forecast");

    foreach ($forecast as $day) {
      $statement = $db->prepare($sql);
      $statement->bindParam(':name', $day["name"], PDO::PARAM_STR);
      $statement->bindParam(':icon', $day["icon"], PDO::PARAM_STR);
      $statement->bindParam(':is_daytime', $day["is_daytime"], PDO::PARAM_INT);
      $statement->bindParam(':temperature', $day["temperature"], PDO::PARAM_STR);
      $statement->bindParam(':forecast', $day["forecast"], PDO::PARAM_STR);
      $statement->bindParam(':detailed', $day["detailed"], PDO::PARAM_STR);
      $statement->execute();
    }


    $db->commit();

    if($config["weather_alerts"]["enabled"]){

      $alerts = self::getAlerts($config);

      $alert_count = 0;
      $sql = "INSERT INTO nws_alerts (`alert`, `link`) VALUES (:alert, :link);";
      $db->beginTransaction();
      $db->query("DELETE FROM nws_alerts");


      foreach($alerts as $alert) {
        if(isset($alert->id)) {
          if($alert->title == "There are no active watches, warnings or advisories") {
            //There are no current alerts.  Clear cache
            break;
          } else {
            //This is an alert
            $statement = $db->prepare($sql);
            $statement->bindParam(':alert', $alert->title, PDO::PARAM_STR);
            $statement->bindParam(':link', $alert->id, PDO::PARAM_STR);
            $statement->execute();
          }
        }
      }

      $db->commit();

    }

    $current_conds = self::getCurrentConditions();

    $tempC = $current_conds["temperature"]["value"];
    $temperature = ($tempC * (9/5)) + 32;

    $db->beginTransaction();
    $db->query("DELETE FROM nws_current;");


    $sql = "INSERT INTO nws_current (`text_description`, `temperature`, `icon`) VALUES (:description, :temperature, :icon);";
    $statement = $db->prepare($sql);

    $statement->bindParam(':description', $current_conds["textDescription"], PDO::PARAM_STR);
    $statement->bindParam(':icon', $current_conds["icon"], PDO::PARAM_STR);
    $statement->bindParam(':temperature', $temperature, PDO::PARAM_INT);
    $statement->execute();

    $db->commit();
  }

  public static function retrieveAlertsCache($db) {
      $sql = "SELECT * FROM nws_alerts";
      $result = $db->query($sql);

      return $result;
  }

  public static function retrieveForecastCache($db) {
    $sql = "SELECT * FROM nws_forecast";
    $result = $db->query($sql);

    return $result;
  }

  public static function retrieveCurrentCache($db) {
    $sql = "SELECT * FROM nws_current";
    $result = $db->query($sql);

    return $result;
  }

}

?>
