<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

use App\Content;

class Client extends Model
{

	/**
	 * @static
	 * @access public
	 * @param  DateTime $sendFrom
	 * @param  DateTime $sendUntil
	 * @return void
	 */
	public static function getSendBatch(DateTime $sendFrom, DateTime $sendUntil)
	{
		$now = new DateTime();
		if ($now >= $sendFrom && $now <= $sendUntil) {
			return $this->where('send_at', '<=', $sendUntil->format('Y-m-d H:i:s'))
				->where('phone', "!=", "")
				->limit(25)
				->get();
		}
	}

	/**
	 * Relationship with Content
	 *
	 * @access public
	 * @return
	 */
	public function content() {
		return $this->belongsTo(Content::class);
	}
}
