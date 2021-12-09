<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{

		//Get the client IP address for use in logging
		helper("ip");
		$ip = get_user_ip();

		//Load the config.json
		$config_dir = implode("/", array_slice(explode("/", FCPATH), 0, -2)) . "/";
		$config_contents = file_get_contents($config_dir . "config.json");
		$config = json_decode($config_contents, true);

		if ($config == null) {
			return "Error reading config file";
		}

		//Leaving this here until I can fix the tenancy issues in the modules
		$nws = new \App\Models\NWS();

		$nws = new \App\Models\NWS();
		$alerts_deduped = $nws::getAlerts($config);

		$body_params = array(
			"user_ip" => $ip,
			"config" => $config
		);

		//Return the headers
		echo view("head", array("config" => $config));

		//Return the navbar, and weather alerts
		echo view("nav", array(
			"alerts" => $alerts_deduped,
			"config" => $config
		));
		echo view("Cards/home_body", $body_params);
		echo view("foot");

	}

}
