<div class="card middle-row-card <?php echo card_dm(); ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $data["card"]["card_title"]; ?></h5>
    <hr>
    <iframe id="radariframe" src="<?php echo $data["card"]["iframe_url"]; ?>" class="radar"></iframe>
  </div>
</div>
