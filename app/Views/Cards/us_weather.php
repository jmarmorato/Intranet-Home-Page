<?php

//Current Conditions
$url = $conditions_url;

$curl_obj = curl_init();

curl_setopt($curl_obj, CURLOPT_URL, $url);
curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_obj, CURLOPT_HTTPHEADER, array(
  'User-Agent: (family-intranet, justin@marmorato.net)',
  //'Accept: application/cap+xml'
));

$out = curl_exec($curl_obj);
$conds = json_decode($out, true)["properties"];

//Forecast
$url = $forecast_url;

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

$forecast = $output;

?>

<div class="card middle-row-card">
  <div class="card-body">
    <h5 class="card-title">Weather</h5>
    <hr>
    <div class="weather-container">

      <?php if(!isset($forecast["error"])): ?>

        <div class="row" style="width:100%; margin-bottom: 0em;">
          <div class="col-9" style="margin-bottom: 0em;">
            <?php
            $tempC = $conds["temperature"]["value"];
            $tempF = ($tempC * (9/5)) + 32;
            ?>
            <p>Now: <?php echo $conds["textDescription"]; ?> and <?php echo round($tempF); ?>&deg;F</p>
          </div>
          <div class="col-3" style="margin-bottom: 0em;">
            <img style="max-width: 100%;" src="<?php echo $conds["icon"]; ?>">
          </div>

        </div>
        <hr>

        <?php foreach($forecast as $period): ?>
          <div class="row" style="width:100%; margin-bottom: 0em;">
            <div class="col-9" style="margin-bottom: 0em;">
              <p><?php echo $period["name"]; ?>: <?php echo $period["forecast"]; ?></p>
              <p style="font-size:.85rem;"><?php if ($period["is_daytime"]) { echo "High "; } else { echo "Low "; } echo $period["temperature"]; ?>&deg;F</p>
            </div>
            <div class="col-3" style="margin-bottom: 0em;">
              <img style="max-width: 100%;" src="<?php echo $period["icon"]; ?>">
            </div>
          </div>
          <hr style="margin-top:0em;">
        <?php endforeach; ?>

      <?php else: ?>

        <p>Error fetching live weather data.  Caching of latest successful data pull will be implimented in future version.</p>

      <?php endif; ?>

    </div>
  </div>
</div>
