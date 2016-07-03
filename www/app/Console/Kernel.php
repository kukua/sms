<?php

namespace App\Console;

use App\Library\Twilio;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	protected $commands = [];

    protected function schedule(Schedule $schedule) {
		$schedule->call(function() {
			$service = new Twilio();
			$service->smsService();
		})->daily();
    }
}
