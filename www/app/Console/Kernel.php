<?php

namespace App\Console;

use App\Library\Twilio;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	protected $commands = [];

    protected function schedule(Schedule $schedule) {
		$schedule->call(function() {

			(new Twilio())->smsService();

		})->dailyAt('04:00');
    }
}
