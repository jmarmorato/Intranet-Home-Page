<?php

$conds = null;

foreach ($data["current"] as $current) {
  $conds = $current;
}

?>

<div class="card <?php if ($data["card_index"] < 3) { echo "top-row-card"; } else { echo "middle-row-card"; } ?> <?php echo card_dm(); ?>">
  <div class="card-body">
    <h5 class="card-title">Weather</h5>
    <hr>
    <div class="weather-container">


      <div class="row" style="width:100%; margin-bottom: 0em;">
        <div class="col-9" style="margin-bottom: 0em;">
          <p>Now: <?php echo $conds["text_description"]; ?> and <?php echo $conds["temperature"] ?>&deg;F</p>
        </div>
        <div class="col-3" style="margin-bottom: 0em;">
          <img style="border-radius:4px; max-width: 100%;" src="<?php echo $conds["icon"]; ?>">
        </div>

      </div>


      <hr>

      <?php foreach($data["forecast"] as $period): ?>
        <div class="row" style="width:100%; margin-bottom: 0em;">
          <div class="col-9" style="margin-bottom: 0em;">
            <p><?php echo $period["name"]; ?>: <?php echo $period["forecast"]; ?></p>
            <p style="font-size:.85rem;"><?php if ($period["is_daytime"]) { echo "High "; } else { echo "Low "; } echo $period["temperature"]; ?>&deg;F</p>
          </div>
          <div class="col-3" style="margin-bottom: 0em;">
            <img style="border-radius:4px; max-width: 100%;" src="<?php echo $period["icon"]; ?>">
          </div>
        </div>
        <hr style="margin-top:0em;">
      <?php endforeach; ?>

    </div>
  </div>
</div>
