<div class="card <?php if ($data["card_index"] < 3) { echo "top-row-card"; } else { echo "middle-row-card"; } ?> <?php echo card_dm(); ?>">
  <div class="card-body">
    <a href="<?php echo $data["card"]["card_link"]; ?>"><h5 class="card-title"><?php echo $data["card"]["card_title"]; ?></h5></a>
    <hr>
    <div class="news-container">
      <?php foreach($data["articles"] as $article): ?>
        <p class='card-text'><a href='<?php echo $article["href"]; ?>'><?php echo $article["title"]; ?></a></p>
      <?php endforeach; ?>
    </div>
  </div>
</div>
