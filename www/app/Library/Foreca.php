<?php

namespace App\Library;

class Foreca {

	public $base;
	public $format;
	public $url;

	public function __construct() {
		$this->base		= env('NAVIFEED_BASEURL', '/');
		$this->format	= env('NAVIFEED_FORMAT', '-');
		$this->url		= $this->base . env('NAVIFEED_URL_FORMAT', '-');
	}

	public function getTemp($lat, $lng) {
		$call = $this->base . '/showdata.php?ftimes=24/24h&format=' . $this->format . '&lon=' . $lng . '&lat=' . $lat . '&tempunit=C&windunit=MS&tz=Europe/Amsterdam';
		return $this->_parse($call);
	}

	public function getRain($lat, $lng) {
		$call = $this->base . '/showdata.php?ftimes=24/23h&format=' . $this->format . '&lon=' . $lng . '&lat=' . $lat . '&tempunit=C&windunit=MS&tz=Europe/Amsterdam';
		return $this->_parse($call);
	}

	/**
	 * Parsing query
	 *
	 * @access protected
	 * @param  String $query (url)
	 * @return Array
	 *
	 * @author Kukua B.V. <dev@kukua.cc>
	 * @since  21-06-2016
	 */
	protected function _parse($query) {
		$response = simplexml_load_file($query);
		if (!$response) {
			return;
		}
		return $response->loc->fc[0];
	}
}
