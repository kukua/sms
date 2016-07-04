<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Geocoder;
use \App\Library\Foreca;
use \App\Library\Twilio;
use \App\Client;

class TextController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function example(Request $request) {
		$city = $request->input('city', 'Amsterdam, NL');
		$param = ["address" => $city];
		$api = json_decode(Geocoder::geocode('json', $param));

		$client = Client::find(1);
		$client->city = $city;

		if (!count($api->results)) {
			$backupParam = ["address" => "Amsterdam, NL"];
			$api = json_decode(Geocoder::geocode('json', $backupParam));
			$client->city = "Amsterdam, NL";
		}

		$client->lat = $api->results[0]->geometry->location->lat;
		$client->lng = $api->results[0]->geometry->location->lng;

		$twilio = (new Twilio())->get($client);
		$data = [
			'datetime'=> $twilio->date->format('d-m-Y'),
			'city' => $twilio->city,
			'tempMin' => $twilio->tempMin,
			'tempMax' => $twilio->tempMax,
			'rain' => $twilio->rainMM
		];
		return view('text/view', $data);
	}
}
