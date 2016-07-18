<?php

namespace App\Console;

use App\Library\Twilio;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	protected $commands = [];

	protected function schedule(Schedule $schedule) {

		//Every 15 minutes
		$schedule->call(function() {
			//(new Twilio())->smsService();
		})->cron('*/15 * * * *');

		//Specific
		$schedule->call(function() {
			(new Twilio())->worldCovrSMS();
		})->dailyAt('18:00');
    }
}
