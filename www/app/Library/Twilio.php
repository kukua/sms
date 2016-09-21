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

		//Date to start the script
		$sendFrom				= (new DateTime())->setTime('04', '00', '00');

		//Date to end the script
		$sendUntil				= (new DateTime())->setTime('08', '00', '00');

		//Date to set after one is done
		$aTimeAfterSendUntil	= (new DateTime())->setTime('09', '00', '00')->format('Y-m-d H:i:s');

		$clients = Client::getSendBatch($sendFrom, $sendUntil);

		if (count($clients)) {
			foreach ($clients as $client) {
				$this->client = $client;
				$object = $this->get();

				if ($object === false) {
					\Log::warning('Invalid client detected: ID #' . $client->id);
					continue;
				}

				$object->_send();
				$this->client->send_at = $aTimeAfterSendUntil;
				$this->client->save();
				sleep(1);
			}
		}
	}

	/**
	 * Get text for client
	 *
	 * @access public
	 * @return App\Library\Twilio | false
	 */
	public function get() {
		if (!$this->client->phone || $this->client->phone == "") {
			return false;
		}

		$this->content = $this->client->content->content;
		return $this;
	}

	/**
	 * Send a message to a number
	 *
	 * @access public
	 * @param  string $to phone number
	 * @return void
	 */
	public function _send() {
		$twilio  = new \Services_Twilio($this->_sId, $this->_token);

		if (!is_null($this->content)) {
			try {
				$twilio->account->messages->sendMessage(
					$this->client->from,
					$this->client->phone,
					$this->content
				);
			} catch (\Exception $e) {
				\Log::warning("Twilio error: " . $e->getMessage());
			}
		}
	}
}

