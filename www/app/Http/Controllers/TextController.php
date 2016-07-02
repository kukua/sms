<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Geocoder;
use \App\Library\Foreca;

class TextController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function example(Request $request) {
		$city = $request->input('city', 'Amsterdam, netherlands');
		$param = ["address" => $city];
		$api = json_decode(Geocoder::geocode('json', $param));

		$data = [
			'datetime'=> new \DateTime(),
			'city' => 'Undefined',
			'tempMin' => '-',
			'tempMax' => '-',
			'rain' => '-'
		];

		if (isset($api->results[0])) {
			$location = $api->results[0]->geometry->location;
			$fcTemp = (new Foreca())->getTemp($location->lat, $location->lng);
			$fcRain = (new Foreca())->getRain($location->lat, $location->lng);

			$datetime = \DateTime::createFromFormat("Y-m-d", $fcTemp['dt'], new \DateTimeZone("Europe/Amsterdam"));
			$tempMin = (float) $fcTemp['tn'];
			$tempMax = (float) $fcTemp['tx'];
			$rain = number_format((float) $fcRain['pr'], 1);

			$data = [
				'datetime'=> $datetime,
				'city' => $city,
				'tempMin' => $tempMin,
				'tempMax' => $tempMax,
				'rain' => $rain
			];
		}
		return view('text/view', $data);
	}
}
