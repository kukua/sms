<?php

/**
 * @author Kukua B.V. <dev@kukua.cc>
 * @since  03-07-2016
 * @copyright -
 * @license -
 */

namespace App\Library;

use App\Client;
use DateTime;

/**
 * @package App
 * @subpackage Library
 */

class Twilio {
	public $client;
	public $forecasts;
	public $content;

	protected $_sId;
	protected $_token;

	public function __construct() {
		$this->_sId    = env('TWILIO_SID', '');
		$this->_token  = env('TWILIO_TOKEN', '');
	}

	/**
	 * Start SMS Service (cron job)
	 *
	 * Get Clients / Send text per client according to its type
	 * Sleep for one second after finished, since Twilio can't handle
	 * too much at the same time.
	 *
	 * @access public
	 * @return void
	 */
	public function smsService() {
		//Create a date before cronjob has served today
		$date = (new DateTime('now'))->setTime('08', '00');
		$clients = Client::getSendBatch($date);

		if (count($clients)) {
			foreach($clients as $client) {
				$object = $this->get($client);
				$object->_send($client->from, $client->phone);
				$client->send_at = (new DateTime())->setTime('09', '00', '00')->format("Y-m-d H:i:s");
				$client->save();
			}
		}
	}

	/**
	 * Get text for client
	 *
	 * @access public
	 * @param  App\Client $client
	 * @param  int | null $type
	 * @return App\Library\Twilio
	 */
	public function get($client, $type = null) {
		if (!$client->phone) {
			return false;
		}

		if ($type == 3) {
			$foreca = (new Foreca())->get24hForecast($client->lat, $client->lng);
		} else {
			$foreca = (new Foreca())->get48hForecast($client->lat, $client->lng);
		}

		foreach($foreca->fc as $fc) {
			$fcAsArray[] = $fc;
		}
		$this->forecasts = $fcAsArray;
		$this->client	 = $client;

		$type = ($type !== null) ? $type : $this->client->type;
		$this->content = $this->getTextFormat($type);
		return $this;
	}

	/**
	 * Call function to format the text by type
	 *
	 * @access public
	 * @param  int $type
	 * @return string $text
	 */
	public function getTextFormat($type) {
		$text = "";
		if (method_exists($this, 'textFormat' . $type)) {
			$text = $this->{"textFormat" . $type}();
		}
		return $text;
	}

	/**
	 * Text format one
	 *
	 * @access public
	 * @return string $str
	 */
	public function textFormat1() {
		$str  = $this->client->city . "\n";
		$i = 0;
		foreach ($this->forecasts as $forecast) {
			if ($i %4 == 0) {
				$str .= $this->dateToWeekday((string) $forecast['dt']) . "\n";
			}
			$str .= $this->dateToDayPart((string) $forecast['dt']) . " ";
			$str .= $this->rainChanceToText((string) $forecast['pp']) . "\n";
			$i++;
		}
		return $str;
	}

	/**
	 * Text format two
	 *
	 * @access public
	 * @return string $str
	 */
	public function textFormat2() {
		$str  = $this->client->city . "\n";
		$i = 0;
		foreach ($this->forecasts as $forecast) {
			if ($i %4 == 0) {
				$str .= $this->dateToWeekday((string) $forecast['dt']) . "\n";
			}
			$str .= $this->dateToDayPart((string) $forecast['dt']) . " ";
			$str .= "Temp " . (string) $forecast['t'] . "\n";
			$i++;

			/* Only forecast for today */
			if ($i %4 == 0) {
				break;
			}
		}
		return $str;
	}

	/**
	 * Text format three
	 *
	 * @access public
	 * @return string $str
	 */
	public function textFormat3()
	{
		$str = "Habari. Hali ya hewa ya ";
		$i = 0;
		foreach($this->forecasts as $forecast) {
			if ($i == 0) {
				$str .= Swahili::day($this->dateToWeekday((string) $forecast["dt"])) . ": ";
			}

			$i++;
		}
		$str .= Swahili::dayTemp((string) $this->forecasts[0]["t"]) . ". ";
		$str .= Swahili::nightTemp((string) $this->forecasts[5]["t"]) . ". ";
		$str .= Swahili::rainChance((string) $this->forecasts[0]["pp"]) . ". ";
		$str .= Swahili::wind((string) $this->forecasts[0]["ws"]) . ". ";
		$str .= "Siku njema, Kukua";
		return $str;
	}

	/**
	 * Send a message to a number
	 *
	 * @access public
	 * @param  string $to phone number
	 * @return void
	 */
	public function _send($from, $to) {
		$twilio  = new \Services_Twilio($this->_sId, $this->_token);

		if (!is_null($this->content)) {
			$twilio->account->messages->sendMessage(
				$from,
				$to,
				$this->content
			);
		}
	}

	/**
	 * Convert rain chance to text
	 *
	 * @access public
	 * @param  int $value
	 * @return string
	 */
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

	/**
	 * Convert date to day part (morning, noon .. etc)
	 *
	 * @access public
	 * @param  string $date
	 * @return string
	 */
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

	/**
	 * Convert date to weekday
	 *
	 * @access public
	 * @param  string $date
	 * @return string
	 */
	public function dateToWeekday($date) {
		$dateTime = \DateTime::createFromFormat("Y-m-d H:i", $date);
		return $dateTime->format("l");
	}

	/**
	 * Specific function for worldCovr
	 *
	 * @access public
	 * @return void
	 */
	public function worldCovrSMS() {
		$phoneOne = '+233208725266';
		$phoneTwo = '+19176707239';
		$phoneThree ='+18603730334';

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
			$forecasts = (new Foreca())->getForecast($geo['lat'], $geo['lng']);

			$object = new Twilio();
			$object->city	 = $geo['city'];
			$object->content = $object->city . "\n";

			$i = 0;
			foreach($forecasts->fc as $fc) {
				if ($i %4 == 0) {
					$object->content .= "\n" . $this->dateToWeekday((string) $fc['dt']) . "\n";
				}
				$object->content .= $this->dateToDayPart((string) $fc['dt']) . " ";
				$object->content .= $this->rainChanceToText((string) $fc['pp']) . "\n";
				$i++;
			}

			if (env('APP_ENV') == 'production') {
				$object->_send("+447400200078", $phoneOne);

				sleep(1);
				$object->_send("+447400200078", $phoneTwo);

				sleep(1);
				$object->_send("+447400200078", $phoneThree);
			}
		}
	}
}
