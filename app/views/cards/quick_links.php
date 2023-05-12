<?php

$card_title = $data["card"]["card_title"];
$links = $data["card"]["links"];

?>

<div class="card <?php if ($data["card_index"] < 3) { echo "top-row-card"; } else { echo "middle-row-card"; } ?> <?php echo card_dm(); ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $card_title; ?></h5>
    <hr>
    <?php foreach($links as $link): ?>
      <p class="card-text"><a href="<?php echo $link["link_url"]; ?>"><?php echo $link["link_text"] ?></a></p>
    <?php endforeach; ?>
  </div>
</div>
