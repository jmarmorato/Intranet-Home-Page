<div class="card top-row-card">
  <div class="card-body">
    <h5 class="card-title"><?php echo $config["card_title"]; ?></h5>
    <hr>
    <a href="<?php echo $image['href']; ?>"><img class="random-sample" src="<?php echo $image['url']; ?>" alt="Random Image"></img></a>
    <p class="card-text"><?php echo $image['comment']; ?></p>
    <hr>
    <a class="card-link"><a href="<?php echo $config["piwigo_url"]; ?>"><?php echo $config["piwigo_link_text"]; ?></a></a>
  </div>
</div>
