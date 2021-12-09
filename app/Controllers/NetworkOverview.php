<?php namespace App\Controllers;

class NetworkOverview extends BaseController
{
	public function index()
	{

		helper("ip");
		$ip = get_user_ip();
		$ip_array = explode(".", $ip);

		if($ip_array[2] == "20") {
			$tenant = "Walter";
		}

		$nmsObj = new \App\Models\NmsApi();
		$okay = $nmsObj->getOkayDevices();
		$critical = $nmsObj->getCriticalDevices();
		$alerts = $nmsObj->getAlerts();
		$services = $nmsObj->getServices();

		$body_params = array(
			"okay" => $okay,
			"critical" => $critical,
			"alerts" => $alerts,
			"services" => $services
		);

		echo view("head");
		echo view("nav");
		echo view("network_overview_body", $body_params);
		echo view("foot");

	}

}
