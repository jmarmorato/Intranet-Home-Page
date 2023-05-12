<div class="card <?php if ($data["card_index"] < 3) { echo "top-row-card"; } else { echo "middle-row-card"; } ?> <?php echo card_dm(); ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $data["card"]["card_title"]; ?></h5>
    <hr>
	<a href="<?php $data["card"]["image_href"]; ?>"><img class="random-sample" src="<?php echo $data["card"]["image_url"]; ?>"></a>
  </div>
</div>
