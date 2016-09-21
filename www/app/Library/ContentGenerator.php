<?php

namespace App\Library;

use App\Content;
use App\Library\Foreca;

class ContentGenerator {

	public $forecast;
	public $content;

	public function __construct() {

	}

    /**
     * @access protected
     */
	public function index() {
		$contentResult = Content::all();
		foreach($contentResult as $content) {
			$this->content = $content;
			$this->getContent();

			//Loop is done, clear content
			$this->content = null;
		}
	}

    /**
     * @access protected
     */
	protected function getContent() {
        $foreca = (new Foreca())->get24hForecast($this->content->lat, $this->content->lng);
        foreach($foreca->fc as $fc) {
            $this->forecast[] = $fc;
        }

		$this->content->content = $this->getTextFormat();
		$this->content->save();
	}

    /**
     * @access protected
     */
	protected function getTextFormat() {
		$text = "";
		if (method_exists($this, 'textFormat' . $this->content->type)) {
			$text = $this->{"textFormat" . $this->content->type}();
		}
		return $text;
	}

	/**
	 * @access public
	 * @return string $str
	 */
	public function textFormat1() {
		$str  = $this->content->city . "\n";
		$i = 0;
		foreach ($this->forecast as $forecast) {
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
	 * @access public
	 * @return string $str
	 */
	public function textFormat2() {
		$str  = $this->content->city . "\n";
		$i = 0;
		foreach ($this->forecast as $forecast) {
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
	 * @access public
	 * @return string $str
	 */
	public function textFormat3()
    {
		$str  = Swahili::header();
		$str .= Swahili::day($this->dateToWeekday((string) $this->forecast[0]["dt"])) . ": ";
		$str .= Swahili::dayTemp((string) $this->forecast[4]["t"]) . ". ";
		$str .= Swahili::nightTemp((string) $this->forecast[9]["t"]) . ". ";
		$str .= Swahili::rainChance((string) $this->forecast[4]["pp"]) . ". ";
		$str .= Swahili::wind((string) $this->forecast[4]["ws"]) . ". ";
		$str .= Swahili::footer();
		return $str;
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
}

