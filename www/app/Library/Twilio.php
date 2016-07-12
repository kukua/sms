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

	public function worldCovrSMS() {
		$phoneOne = '+233208725266';
		$phoneTwo = '+19176707239';
		$phoneThree='+18603730334';

		$locations = [
			[
				'city' => 'Beoboken',
				'lat' => '10.88515',
				'lng' => '-0.75446',
			],
			[
				'city' => 'Dabogshei',
				'lat' => '9.268018',
				'lng' => '-0.871342',
			],
			[
				'city' => 'Takpili',
				'lat' => '9.300931',
				'lng' => '-0.5784113',
			],
			[
				'city' => 'Gowrie',
				'lat' => '10.84835',
				'lng' => '-0.85098',
			],
			[
				'city' => 'Tonogugro',
				'lat' => '10.88339',
				'lng' => '-0.726315',
			],
			[
				'city' => 'Toroyili',
				'lat' => '9.33635',
				'lng' => '-1.071362',
			],
			[
				'city' => 'Zoopele',
				'lat' => '11.0172',
				'lng' => '-2.805917',
			],
			[
				'city' => 'Bekyiinteng',
				'lat' => '10.979426',
				'lng' => '-2.79559',
			]
		];


		foreach($locations as $geo) {
			$rain = (new Foreca())->worldCovr($geo['lat'], $geo['lng']);

			$object = new Twilio();
			$object->city	 = $geo['city'];
			$object->content = $object->city . "\n";

			$i = 0;
			foreach($rain->fc as $fc) {
				if ($i %4 == 0) {
					$object->content .= "\n" . $this->dateToWeekday((string) $fc['dt']) . "\n";
				}
				$object->content .= $this->dateToDayPart((string) $fc['dt']) . " ";
				$object->content .= $this->rainChanceToText((string) $fc['pp']) . "\n";
				$i++;
			}

			if (env('APP_ENV') == 'production') {
				$object->_send($phoneOne);

				sleep(1);
				$object->_send($phoneTwo);

				sleep(1);
				$object->_send($phoneThree);
			}
		}
	}

	public function smsService() {
		$clients = Client::all();
		$i = 0;
		foreach($clients as $client) {
			$object = $this->get($client);
			$object->_send($client->phone);

			//Set timeout for API calls (max 10/s)
			$i ++;
			if ($i %9 == 0) {
				sleep(1);
			}
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

	public function rainChanceToText($value) {
		if ($value <= 10) {
			return "No rain";
		} elseif ($value <= 30) {
			return "Minimal chance of rain";
		} elseif ($value <= 50) {
			return "Little chance of rain";
		} elseif ($value <= 69) {
			return "Likely to rain";
		} else {
			return "It will rain";
		}
	}

	public function dateToDayPart($date) {
		$dateTime = \DateTime::createFromFormat("Y-m-d H:i", $date);
		$convert = $dateTime->format('H');

		if ($convert >= 6 && $convert < 12 ) {
			return 'Morning';
		}
		if ($convert >= 12 && $convert < 17) {
			return 'Noon';
		}
		if ($convert >= 17 && $convert < 23) {
			return 'Evening';
		}
		if ($convert >= 0 && $convert < 6) {
			return 'Night';
		}
	}

	public function dateToWeekday($date) {
		$dateTime = \DateTime::createFromFormat("Y-m-d H:i", $date);
		return $dateTime->format("l");
	}
}
