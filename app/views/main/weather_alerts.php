<div class="weather-alert-container">
  <?php foreach($data["alerts"] as $alert): ?>
    <?php if(str_contains(strtolower($alert["alert"]), "hurricane") || str_contains(strtolower($alert["alert"]), "tornado") || str_contains(strtolower($alert["alert"]), "severe")): ?>
      <div class="weather-alert alert alert-danger" style="margin-top: 1em; margin-left:auto; margin-right: auto;" role="alert">
        <a href="<?php echo $alert["link"] ?>"><?php echo $alert["alert"] ?></a>
      </div>
    <?php else: ?>
      <div class="weather-alert alert alert-warning" style="margin-top: 1em; margin-left:auto; margin-right: auto;" role="alert">
        <a href="<?php echo $alert["link"] ?>"><?php echo $alert["alert"] ?></a>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>
