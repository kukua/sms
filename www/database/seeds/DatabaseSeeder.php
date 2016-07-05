<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->insert([
			'id'		=> 1,
			'name'		=> 'Kukua Team',
			'email'		=> 'info@kukua.cc',
			'password'	=> \Hash::make(env('DEFAULT_PASSWORD', 'SomeRandomPassword'))
		]);

		DB::table('clients')->insert([
			'id'	=> 1,
			'type'	=> 1,
			'name'	=> 'Siebren Kranenburg',
			'phone'	=> '+31681188772',
			'city'	=> 'Utrecht, NL',
			'lat'	=> '52.0907',
			'lng'	=> '5.12142'
		]);
	}
}
