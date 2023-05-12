<div class="card <?php if ($data["card_index"] < 3) { echo "top-row-card"; } else { echo "middle-row-card"; } ?> <?php echo card_dm(); ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $data["card"]["card_title"]; ?></h5>
    <hr>
    <img class="random-sample" src="<?php echo $data["image"]['url']; ?>" alt="Random Image" />
  </div>
</div>
