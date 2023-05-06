<?php

class Local_Random_Image {
    /*
     * This class handles chosing and displaying a random image from a local
     * directory.  Given that images are stored locally, there's no need to
     * cache information about them in the database.
     */
    
    public static function getRandomImage() {
	
	$directory = APPPATH . "/public/images/";
		
	$images = array_diff(scandir($directory), array('..', '.', '.gitignore'));
		
	$random_image = $images[rand(0, count($images) - 1) + 3];
		
	return array(
	    "url" => "/images/" . $random_image
	);
    }
}

?>