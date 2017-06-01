<?php

namespace App\Console\Commands;

use App\Http\Controllers;
use Illuminate\Console\Command;

class SendBookingReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendBookingReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send booking reminder to clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $scraperObj = new Controllers\BookingsController();

        $this->comment(PHP_EOL.'Sending booking reminders started...'.PHP_EOL);
        if ($scraperObj->getSendReminders()) {
            $this->comment(PHP_EOL . 'Sending booking reminders complete :)' . PHP_EOL);
        } else {
            $this->comment(PHP_EOL . 'Error identified in sending booking reminders' . PHP_EOL);
        }
    }
}
