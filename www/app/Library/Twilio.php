<?php

/**
 * @author Kukua B.V. <dev@kukua.cc>
 * @since  03-07-2016
 * @copyright -
 * @license -
 */

namespace App\Library;

use App\Client;

/**
 * @package App
 * @subpackage Library
 */

class Twilio {

	public $date;
	public $city;
	public $tempMin;
	public $tempMax;
	public $rainMM;
	public $rainChance;
	public $content;

	protected $_sId;
	protected $_token;
	protected $_number;

	public function __construct() {
		$this->_sId    = env('TWILIO_SID', '');
		$this->_token  = env('TWILIO_TOKEN', '');
		$this->_number = env('TWILIO_NUMBER', '');
	}

	public function specialSMS() {
		$phoneOne = '+31681188772';
		$phoneTwo = '+31610573692';

		$locations = [
			[
				'lat' => '10.88515',
				'lng' => '-0.75446',
			],
			[
				'lat' => '9.268018',
				'lng' => '-0.871342',
			],
			[
				'lat' => '9.300931',
				'lng' => '-0.5784113',
			],
			[
				'lat' => '10.84835',
				'lng' => '-0.85098',
			],
			[
				'lat' => '10.88339',
				'lng' => '-0.726315',
			],
			[
				'lat' => '9.33635',
				'lng' => '-1.071362',
			],
			[
				'lat' => '11.0172',
				'lng' => '-2.805917',
			],
			[
				'lat' => '10.979426',
				'lng' => '-2.79559',
			]
		];


		foreach($locations as $geo) {
			$temp = (new Foreca())->getTemp($geo['lat'], $geo['lng']);
			$rain = (new Foreca())->getRain($geo['lat'], $geo['lng']);

			$object = new Twilio();
			$object->date    = \DateTime::createFromFormat('Y-m-d', $temp['dt'], new \DateTimeZone("Europe/Amsterdam"));
			$object->tempMin = (float) $temp['tn'];
			$object->tempMax = (float) $temp['tx'];
			$object->rainMM  = number_format((float) $rain['pr'], 1);
			$object->content = $object->textFormatSpecial();

			$object->_send($phoneOne);
			$object->_send($phoneTwo);
		}
	}

	public function smsService() {
		$clients = Client::all();
		foreach($clients as $client) {
			$object = $this->get($client);
			$object->_send($client->phone);
		}
	}

	public function get($client, $type = null) {
		if (!$client->phone) {
			return false;
		}

		$temp = (new Foreca())->getTemp($client->lat, $client->lng);
		$rain = (new Foreca())->getRain($client->lat, $client->lng);

		$this->date    = \DateTime::createFromFormat('Y-m-d', $temp['dt'], new \DateTimeZone("Europe/Amsterdam"));
		$this->city    = $client->city;
		$this->tempMin = (float) $temp['tn'];
		$this->tempMax = (float) $temp['tx'];
		$this->rainMM  = number_format((float) $rain['pr'], 1);

		if ($type !== null) {
			$this->content = $this->getTextFormat($type);
		} else {
			$this->content = $this->getTextFormat($client->type);
		}

		if ($this->content === null) {
			return false;
		}

		return $this;
	}

	public function getTextFormat($type) {
		if ($type == 1) {
			return $this->textFormatOne();
		}
		if ($type == 2) {
			return $this->textFormatTwo();
		}
		return null;
	}

	public function textFormatOne() {
		$str  = $this->city . ", " . $this->date->format('d-m-Y') . "\r\n";
		$str .= "Temp min: " . $this->tempMin . "℃ \r\n";
		$str .= "Temp max: " . $this->tempMax . "℃ \r\n";
		$str .= "Rainfall: " . $this->rainMM;
		return $str;
	}

	public function textFormatTwo() {
		$str  = $this->date->format('d-m-Y') . ", " . $this->city . "\r\n";
		$str .= "Rainfall: " . $this->rainMM . "\r\n";
		$str .= "Temp min: " . $this->tempMin . "℃ \r\n";
		$str .= "Temp max: " . $this->tempMax . "℃";
		return $str;
	}

	public function textFormatSpecial() {
		$str  = $this->date->format('d-m-Y') . "\r\n";
		$str .= "Temp min: " . $this->tempMin . "℃ \r\n";
		$str .= "Temp max: " . $this->tempMax . "℃" . "\r\n";
		$str .= "Rainfall: " . $this->rainMM;
		return $str;
	}

	public function _send($to) {
		$twilio  = new \Services_Twilio($this->_sId, $this->_token);

		if (!is_null($this->content)) {
			$twilio->account->messages->sendMessage(
				$this->_number,
				$to,
				$this->content
			);
		}
	}
}
