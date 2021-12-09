<div class="card middle-row-card">
  <div class="card-body">
    <h5 class="card-title"><?php echo $card_title; ?></h5>
    <hr>

    <div class="news-container">

      <?php
      $newsFeed = file_get_contents($rss_url);
      $newsObj = simplexml_load_string($newsFeed);
      $articles = array();
      $i = $num_items;
      foreach($newsObj->channel->item as $article) {
        echo "<p class='card-text'><a href='$article->link'>$article->title</a></p>";
        $i--;
        if ($i == 0) {
          break;
        }
      }
      ?>
    </div>
    <hr>
    <a class="card-link" href="<?php echo $card_link; ?>"><?php echo $card_link_text; ?></a>
  </div>
</div>
