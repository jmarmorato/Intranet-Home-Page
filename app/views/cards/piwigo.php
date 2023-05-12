<div class="card <?php if ($data["card_index"] < 3) { echo "top-row-card"; } else { echo "middle-row-card"; } ?> <?php echo card_dm(); ?>">
  <div class="card-body">
    <a href="<?php echo $data["card"]["piwigo_url"]; ?>"><h5 class="card-title"><?php echo $data["card"]["card_title"]; ?></h5></a>
    <hr>
    <a href="<?php echo $data["image"]['href']; ?>"><img class="random-sample" src="<?php echo $data["image"]['url']; ?>" alt="Random Image"></img></a>
    <p class="card-text"><?php echo $data["image"]['comment']; ?></p>
  </div>
</div>
