<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log detail schedule times';

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
        \Log::channel('scheduleLog')->info('======= Start - Schedule Command at '.date('Y-m-d H:i:s').' =======');
    }
}
