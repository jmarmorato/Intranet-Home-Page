<?php

class Rss {

  public static function updateCache($db, $card) {
    $newsFeed = file_get_contents($card["rss_url"]);
    $newsObj = simplexml_load_string($newsFeed);
    $articles = array();

    if (!is_null($newsFeed)) {
      $db->beginTransaction();
      $sql = "DELETE FROM rss WHERE `feed`=:feed;";
      $statement = $db->prepare($sql);
      $statement->bindParam(':feed', $card["rss_url"], PDO::PARAM_STR);
      $statement->execute();
      $sql = "INSERT INTO rss (`feed`, `title`, `href`) VALUES (:feed, :title, :href);";

      foreach($newsObj->channel->item as $article) {

        $statement = $db->prepare($sql);
        $statement->bindParam(':feed', $card["rss_url"], PDO::PARAM_STR);
        $statement->bindParam(':title', $article->title, PDO::PARAM_STR);
        $statement->bindParam(':href', $article->link, PDO::PARAM_STR);
        $statement->execute();

      }

      $db->commit();
    }

  }

  public static function retrieveRSSCache($db, $card) {
    $feed_url = $card["rss_url"];

    $sql = "SELECT `title`, `href` FROM rss WHERE `feed` = '" . $feed_url . "';";

    return $db->query($sql);
  }



}

?>
