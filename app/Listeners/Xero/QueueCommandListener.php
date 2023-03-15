<?php

namespace App\Listeners\Xero;

use App\Jobs\Xero\QueueCommandJob;
use App\Events\Xero\QueueCommandEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueCommandListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  QueueCommandEvent  $event
     * @return void
     */
    public function handle(QueueCommandEvent $event)
    {
        \Log::channel('xeroLog')->info('Start Xero Queue Command Event');
        try {
            dispatch((new QueueCommandJob())->onQueue('xero'))->delay(now()->addMinutes(30));
            \Log::channel('xeroLog')->info('End Xero Queue Command Event');
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
}
