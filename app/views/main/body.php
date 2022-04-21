<body>

  <?php echo view("/main/nav", array(
    "config" => $data["config"]
  )
); ?>

<!--Insert weather alert banner if enabled-->
<?php if($data["config"]["weather_alerts"]["enabled"]): ?>
  <?php echo view("main/weather_alerts", array(
    "alerts" => $data["alerts"]
  )); ?>
<?php endif; ?>

<div class="container-fluid main-container">

  <?php foreach($data["config"]["cards"] as $card): ?>

    <?php if($card["type"] == "quick_links"): ?>
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <!--Insert Quick Links Card-->
        <?php echo view("cards/quick_links", array(
          "card" => $card
        )); ?>
      </div>

    <?php elseif($card["type"] == "us_weather"): ?>

      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <!--Insert Current Conditions Card-->
        <?php echo view("cards/us_weather", array(
          "forecast" => $data["forecast"],
          "current"  => $data["current"]
        )); ?>
      </div>

    <?php elseif($card["type"] == "piwigo"): ?>

      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <!--Insert Current Conditions Card-->
        <?php echo view("cards/piwigo", array(
          "config" => $data["config"],
          "image"  => $data["random_image"],
          "card"   => $card
        )); ?>
      </div>

    <?php elseif($card["type"] == "image"): ?>

      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <!--Insert Current Conditions Card-->
        <?php echo view("cards/image", array(
          "config" => $data["config"],
          "card"   => $card
        )); ?>
      </div>

    <?php elseif($card["type"] == "rss"): ?>

      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <!--Insert Current Conditions Card-->
        <?php echo view("cards/rss", array(
          "config" => $data["config"],
          "card"   => $card,
          "articles" => $data["articles"]
        )); ?>
      </div>

    <?php elseif($card["type"] == "caldav"): ?>

      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <!--Insert Current Conditions Card-->
        <?php echo view("cards/caldav", array(
          "config" => $data["config"],
          "card"   => $card,
          "events" => $data["events"]
        )); ?>
      </div>

    <?php endif; ?>

  <?php endforeach; ?>

</div>


</body>
<script src="/script.js"></script>
