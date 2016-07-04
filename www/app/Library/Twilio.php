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
		$str .= "Temperature min: " . $this->tempMin . "℃ \r\n";
		$str .= "Temperature max: " . $this->tempMax . "℃ \r\n";
		$str .= "Expected rainfall: " . $this->rainMM;
		return $str;
	}

	public function textFormatTwo() {
		$str  = $this->date->format('d-m-Y') . ", " . $this->city . "\r\n";
		$str .= "Expected rainfall: " . $this->rainMM . "\r\n";
		$str .= "Temperature min: " . $this->tempMin . "℃ \r\n";
		$str .= "Temperature max: " . $this->tempMax . "℃";
		return $str;
	}

	protected function _send($to) {
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
