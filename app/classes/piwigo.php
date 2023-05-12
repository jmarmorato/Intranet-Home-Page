<?php

class Piwigo {

    /*
     * This function only runs during cache updates (cron)
     */
    public static function getImages($data) {

	/*
	 * Create Piwigo API URL
	 */
	$url = $data["piwigo_url"] . "/ws.php?format=json&method=pwg.categories.getImages&cat_id=" . $data["piwigo_album_id"];

	/*
	 * Call Piwigo API URL and recieve album photos
	 */
	$piwigo_json = file_get_contents($url);
	$result = json_decode($piwigo_json);
	
	
	/*
	 * Return images
	 */
	$images = $result->result->images;
	return $images;
    }

    /*
     * This function only runs during cache updates (cron)
     */
    public static function updateCache($db, $config) {
	
	/*
	 * Get images from Piwigo
	 */
	$images = self::getImages($config);
	
	$sql = "INSERT INTO piwigo (`url`, `comment`, `href`) VALUES (:url, :comment, :href);";

	if ($images) {
	    /*
	     * We use a transaction so we can instantly commit the new images to
	     * the database.  This prevents a user from loading the page
	     * inbetween a delete and insert operation and having no image
	     */
	    $db->beginTransaction();
	    
	    /*
	     * Delete the old image cache
	     */
	    $db->query("DELETE FROM piwigo;");

	    /*
	     * Insert the new images
	     */
	    foreach ($images as $image) {
		$statement = $db->prepare($sql);

		$statement->bindParam(':url', $image->derivatives->small->url, PDO::PARAM_STR);
		$statement->bindParam(':comment', $image->comment, PDO::PARAM_STR);
		$statement->bindParam(':href', $image->page_url, PDO::PARAM_STR);
		$statement->execute();
	    }

	    /*
	     * Commit the new images to the database.  When this happens, the old
	     * images disappear and the new images appear instantly.
	     */
	    $db->commit();
	}
    }

    
    /*
     * This is the function that runs when the page is loded
     */
    public static function getRandomImage($db) {

	/*
	 * Get the list of images from the database
	 */
	$images = array();
	$results = $db->query("SELECT * FROM piwigo");
	foreach ($results as $image) {
	    array_push($images, $image);
	}

	/*
	 * Select and return a random image
	 */
	$random_image = $images[rand(0, count($images) - 1)];
	return $random_image;
    }

}

?>
