<div class="card top-row-card">
  <div class="card-body">
    <a href="<?php echo $data["card"]["piwigo_url"]; ?>"><h5 class="card-title"><?php echo $data["card"]["card_title"]; ?></h5></a>
    <hr>
    <a href="<?php echo $data["image"]['href']; ?>"><img class="random-sample" src="<?php echo $data["image"]['url']; ?>" alt="Random Image"></img></a>
    <p class="card-text"><?php echo $data["image"]['comment']; ?></p>
  </div>
</div>
