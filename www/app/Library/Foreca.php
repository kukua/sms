<?php

namespace App\Library;

class Foreca {

	public $base;
	public $format;
	public $url;

	/**
	 * Class constructor
	 *
	 * @access protected
	 * @return Void
	 *
	 * @author Kukua B.V. <dev@kukua.cc>
	 * @since  21-06-2016
	 */
	public function __construct() {
		$this->base		= env('NAVIFEED_BASEURL', '/');
		$this->format	= env('NAVIFEED_FORMAT', '-');
		$this->url		= $this->base . env('NAVIFEED_URL_FORMAT', '-');
	}

	/**
	 * Get Temperature from Foreca (24/24)
	 *
	 * @access protected
	 * @param  String $lat
	 * @param  String $lng
	 * @return Array
	 *
	 * @author Kukua B.V. <dev@kukua.cc>
	 * @since  21-06-2016
	 */
	public function getTemp($lat, $lng) {
		$call = $this->base . 'showdata.php?ftimes=48/24h&format=' . $this->format . '&lon=' . $lng . '&lat=' . $lat . '&tempunit=C&windunit=MS&tz=UTC';
		return $this->_parse($call);
	}

	/**
	 * Get rain from Foreca (24/23)
	 *
	 * @access protected
	 * @param  String $lat
	 * @param  String $lng
	 * @return Array
	 *
	 * @author Kukua B.V. <dev@kukua.cc>
	 * @since  21-06-2016
	 */
	public function getRain($lat, $lng) {
		$call = $this->base . 'showdata.php?ftimes=24/23h&format=' . $this->format . '&lon=' . $lng . '&lat=' . $lat . '&tempunit=C&windunit=MS&tz=UTC';
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

	/**
	 * WorldCovr specific
	 *
	 * @access public
	 * @param  float $lat
	 * @param  float $lng
	 * @return SimpleXMLObject
	 */
	public function worldCovr($lat, $lng) {
		$call = $this->base . 'showdata.php?ftimes=48/6h&lon=' . $lng . '&lat=' . $lat . '&format=' . $this->format . '&tempunit=C&windunit=MS&tz=UTC';
		return $this->parseWorldCovr($call);
	}

	/**
	 * @access public
	 * @param  string $query
	 * @return SimpleXMLObject
	 */
	public function parseWorldCovr($query) {
		$response = simplexml_load_file($query);
		return $response->loc;
	}
}
