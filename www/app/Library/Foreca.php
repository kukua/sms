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
	 * Get data from Foreca (48/6)
	 *
	 * @access protected
	 * @param  String $lat
	 * @param  String $lng
	 * @return Array
	 *
	 * @author Kukua B.V. <dev@kukua.cc>
	 * @since  21-06-2016
	 */
	public function get48hForecast($lat, $lng) {
		$call = $this->base . 'showdata.php?ftimes=48/6h&format=' . $this->format . '&lon=' . $lng . '&lat=' . $lat . '&tempunit=C&windunit=MS&tz=UTC';
		return $this->_parse($call);
	}

	/**
	 * Get data from Foreca (48/6)
	 *
	 * @access protected
	 * @param  String $lat
	 * @param  String $lng
	 * @return Array
	 *
	 * @author Kukua B.V. <dev@kukua.cc>
	 * @since  17-07-2016
	 */
	public function get24hForecast($lat, $lng) {
		$call = $this->base . 'showdata.php?ftimes=12/2h&format=' . $this->format . '&lon=' . $lng . '&lat=' . $lat . '&tempunit=C&windunit=MS&tz=UTC';
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
        libxml_use_internal_errors();
        $response = simplexml_load_file($query);
        if (!$response) {
			return;
		}

		return $response->loc;
	}
}
