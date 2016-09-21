<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;

class Content extends Model {

	protected $table = 'content';

	public function getCities() {
		return $this->groupBy('city')->get();
	}

	public function getTypes() {
		return $this->groupBy('type')->get();
	}

	public function findByCityAndType($city, $type) {
		return $this->where('city', $city)
			->where('type', $type)
			->first();
	}

	public function clients() {
		return $this->hasMany(Client::class);
	}
}
