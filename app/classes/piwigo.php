<?php

class Piwigo {

  public static function getImages($data) {

    //Get Piwigo URL and album ID from config file.
    $piwigo_url = getenv("piwigoUrl");
    $piwigo_catUrl = getenv("piwigoCatId");

    $url = $data["piwigo_url"] . "/ws.php?format=json&method=pwg.categories.getImages&cat_id=" . $data["piwigo_album_id"];

    $piwigo_json = file_get_contents($url);
    $result = json_decode($piwigo_json);
    $count_images = $result->result->paging->total_count;
    $images = $result->result->images;

    return $images;

  }

  public static function updateCache($db, $config) {
    $images = self::getImages($config);
    $sql = "INSERT INTO piwigo (`url`, `comment`, `href`) VALUES (:url, :comment, :href);";

    if ($images) {
      $db->beginTransaction();
      $db->query("DELETE FROM piwigo;");

      foreach ($images as $image) {
        $statement = $db->prepare($sql);

        $statement->bindParam(':url', $image->derivatives->small->url, PDO::PARAM_STR);
        $statement->bindParam(':comment', $image->comment, PDO::PARAM_STR);
        $statement->bindParam(':href', $image->page_url, PDO::PARAM_STR);
        $statement->execute();
      }

      $db->commit();
    }

  }

  public static function getRandomImage($db) {

    $images = array();

    $results = $db->query("SELECT * FROM piwigo");

    foreach ($results as $image) {
      array_push($images, $image);
    }

    $random_image = $images[rand(0, count($images) - 1)];

    return $random_image;
  }

}
?>
