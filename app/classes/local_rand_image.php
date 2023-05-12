<?php

class Local_Random_Image {
    /*
     * This class handles chosing and displaying a random image from a local
     * directory.  Given that images are stored locally, there's no need to
     * cache information about them in the database.
     */

    public static function getRandomImage() {

	/*
	 * Build string for directory path
	 */
	$directory = APPPATH . "/public/images/";
	
	/*
	 * Get a list of all files in the images directory, ignoring ., .., and
	 * .gitignore.
	 */
	$images = array_diff(scandir($directory), array('..', '.', '.gitignore'));

	/*
	 * Pick a random image from the array of images.  The +3 at the end is
	 * needed because arraydiff() removes the specified array elements, but
	 * leaves the index.  Without this, we would have array index out of
	 * bounds issues, and the first three images would always be inaccessable
	 */
	$random_image = $images[rand(0, count($images) - 1) + 3];

	/*
	 * Return image relative path (/images/filename.jpg)
	 */
	return array(
	    "url" => "/images/" . $random_image
	);
    }

}

?>