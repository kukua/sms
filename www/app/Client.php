<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Client extends Model
{
	public static function getSendBatch($date)
	{
		$now	= new DateTime();
		$go		= (new DateTime())->setTime('04', '00', '00');

		if ($go >= $now) {
			return Client::where('send_at', '<', $date)
				->where('send_at', '>', $go->format('Y-m-d H:i:s'))
				->limit(25)
				->get();
		}
	}
}
