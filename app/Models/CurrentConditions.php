<?php namespace App\Models;

use CodeIgniter\Model;

class CurrentConditions extends Model
{

  //This class provides the current weather conditions using the Cisco Weather
  //service api

  public static function getCurrentConditions() {

    //Get current conditions from Cisco XML App Service
    $current_conditions_xml = file_get_contents("http://xml.ipa.marmorato.net/current.php");
    $xml_obj = simplexml_load_string($current_conditions_xml);

    $day_rain_xml = file_get_contents("http://xml.ipa.marmorato.net/past24hours.php");
    $day_obj = simplexml_load_string($day_rain_xml);

    if ($xml_obj->Title == "Signal Error") {
      return array(
        "error" => "sensor"
      );
    }

    return array(
      "temp"    => explode(" ", strval($xml_obj->MenuItem[0]->Name))[2],
      "wspeed"  => explode(" ", strval($xml_obj->MenuItem[3]->Name))[2],
      "humidity"=> explode(" ", strval($xml_obj->MenuItem[5]->Name))[1],
      "wchill"  => explode(" ", strval($xml_obj->MenuItem[2]->Name))[2],
      "hindex"  => explode(" ", strval($xml_obj->MenuItem[1]->Name))[2],
      "pressure"  => explode(" ", strval($xml_obj->MenuItem[4]->Name))[1],
      "rainTotal" => explode(" ", strval($day_obj->MenuItem[2]->Name))[2]
    );
  }

}
