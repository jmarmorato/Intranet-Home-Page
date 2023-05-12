<?php

class Rss {

    /*
     * This function only runs during cache updates (cron)
     */
    public static function updateCache($db, $card) {
	
	/*
	 * Get feed articles
	 */
	$newsFeed = file_get_contents($card["rss_url"]);
	$newsObj = simplexml_load_string($newsFeed);
	$articles = array();

	/*
	 * If articles were successfully retrieved, start a transaction.  This
	 * prevents a user from loading the page inbetween a delete and insert
	 * operation and having no articles.
	 */
	if (!is_null($newsFeed)) {
	    $db->beginTransaction();
	    
	    /*
	     * Delete the old articles from this feed
	     */
	    $sql = "DELETE FROM rss WHERE `feed`=:feed;";
	    $statement = $db->prepare($sql);
	    $statement->bindParam(':feed', $card["rss_url"], PDO::PARAM_STR);
	    $statement->execute();
	    
	    /*
	     * Insert the new articles
	     */
	    $sql = "INSERT INTO rss (`feed`, `title`, `href`) VALUES (:feed, :title, :href);";

	    foreach ($newsObj->channel->item as $article) {

		$statement = $db->prepare($sql);
		$statement->bindParam(':feed', $card["rss_url"], PDO::PARAM_STR);
		$statement->bindParam(':title', $article->title, PDO::PARAM_STR);
		$statement->bindParam(':href', $article->link, PDO::PARAM_STR);
		$statement->execute();
	    }

	    /*
	     * Commit the new articles to the database.  When this happens, the
	     * old articles disappear and the new articles appear instantly.
	     */
	    $db->commit();
	}
    }

    /*
     * This is the function that runs when the page is loded
     */
    public static function retrieveRSSCache($db, $card) {
	/*
	 * Get the feed URL from config
	 */
	$feed_url = $card["rss_url"];
	
	/*
	 * Select and return items from that feed
	 */
	$sql = "SELECT `title`, `href` FROM rss WHERE `feed` = '" . $feed_url . "';";
	return $db->query($sql);
    }

}

?>
