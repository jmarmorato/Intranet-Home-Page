<div class="container-fluid main-container"">

  <!--Insert weather alert banner if enabled-->
  <?php if($config["weather_alerts"]["enabled"]): ?>
    <?php echo view("weather_alert", array("alerts" => $alerts)); ?>
  <?php endif; ?>

  <?php $cards_printed = 0; $num_cards = count($config["cards"]); ?>

  <?php foreach($config["cards"] as $card): ?>

    <?php if($cards_printed == 0): ?>
      <div class="row">
      <?php elseif($cards_printed % 3 == 0): ?>
      </div>
      <div class="row">
      <?php endif; ?>

      <?php $cards_printed++; ?>
      <?php if($card["type"] == "caldav"): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
          <!--Insert Upcoming Events Card-->
          <?php echo view("Cards/caldav", $card); ?>
        </div>
      <?php elseif($card["type"] == "piwigo"): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
          <!--Insert Piwigo Random Sample Card-->
          <?php

          //Get Piwigo URL and album ID from config file.
          $piwigo_url = getenv("piwigoUrl");
          $piwigo_catUrl = getenv("piwigoCatId");

          $url = $card["piwigo_url"] . "/ws.php?format=json&method=pwg.categories.getImages&cat_id=" . $card["piwigo_album_id"];

          $piwigo_json = file_get_contents($url);
          $result = json_decode($piwigo_json);
          //var_dump($result->result);
          $count_images = $result->result->paging->total_count;
          $images = $result->result->images;
          $image = $images[rand(0, $count_images - 1)];
          $rand_image_url = $image->derivatives->small->url;

          $image = array(
            "url" => $rand_image_url,
            "comment" => $image->comment,
            "href" => $image->page_url
          );

          echo view("Cards/piwigo", array(
            "image" => $image,
            "config" => $card
          ));

          ?>
        </div>
      <?php elseif($card["type"] == "quick_links"): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
          <!--Insert Quick Links Card-->
          <?php echo view("Cards/quick_links", $card); ?>
        </div>
      <?php elseif($card["type"] == "rss"): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
          <!--Insert World News Card-->
          <?php echo view("Cards/rss", $card); ?>
        </div>
      <?php elseif($card["type"] == "image"): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
          <!--Insert Radar Loop Card-->
          <?php echo view("Cards/image", $card); ?>
        </div>
      <?php elseif($card["type"] == "us_weather"): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
          <!--Insert Current Conditions Card-->
          <?php echo view("Cards/us_weather", $card); ?>
        </div>
      <?php elseif($card["type"] == "librenms"): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
          <!--Insert System Outages Card-->
          <?php echo view("Cards/system_outages", $card); ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>








  <div class="row">


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
    </div>

  </div>
</div>
</div>
</body>
<script src="/bloodhound.js"></script>
<script src="/script.js"></script>
