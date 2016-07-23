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
			ob_start();
			(new Twilio())->smsService();
			file_put_contents(
				'/tmp/dump.txt',
				'---' . (string) new DateTime() . "---\n" . ob_get_clean(),
				FILE_APPEND
			);
		})->cron('*/15 * * * *');

		//Specific
		$schedule->call(function() {
			(new Twilio())->worldCovrSMS();
		})->dailyAt('18:00');
    }
}
