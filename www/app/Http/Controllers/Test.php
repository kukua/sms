<?php

namespace App\Http\Controllers;

use Geocoder;
use \App\Library\Foreca;

class Test extends Controller {

	public function __construct() {

	}

	public function index() {
		$param = array("address"=>"Arusha, Nigeria");
		$api = json_decode(Geocoder::geocode('json', $param));

		$location = $api->results[0]->geometry->location;
		$fcTemp = (new Foreca())->getTemp($location->lat, $location->lng);
		$fcRain = (new Foreca())->getRain($location->lat, $location->lng);

		$datetime = \DateTime::createFromFormat("Y-m-d H:i", $fcRain['dt'], new \DateTimeZone("Europe/Amsterdam"));
		$tempMin = (float) $fcTemp['tn'];
		$tempMax = (float) $fcTemp['tx'];
		$rain = number_format((float) $fcRain['pr'], 1);

		echo "<pre>";

		echo "Arusha " . $datetime->format('d-m-Y') . "\n";
		echo "Temperature min: $tempMin ℃ \n";
		echo "Temperature max: $tempMax ℃ \n";
		echo "Total rain: $rain mm";

		echo "</pre>";
		die();
		return view('welcome');
	}
}
