<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Library\Twilio;
use App\Library\ContentGenerator;

class Kernel extends ConsoleKernel {

	protected $commands = [];

	protected function schedule(Schedule $schedule) {

		//Every 15 minutes
		$schedule->call(function() {
			(new Twilio())->smsService();
        })->cron('*/15 * * * *');

        $schedule->call(function() {
            (new ContentGenerator())->index();
        })->cron(0 3 * * *);
    }
}

