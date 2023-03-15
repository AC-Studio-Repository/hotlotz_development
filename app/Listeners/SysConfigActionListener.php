<?php

namespace App\Listeners;

use App\Events\SysConfigActionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SysConfigActionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SysConfigActionEvent  $event
     * @return void
     */
    public function handle(SysConfigActionEvent $event)
    {
        \Log::info('Start - SysConfigActionEvent');
        
        $action = $event->data;
        \Log::info('System Configuration is '.$action.'.');

        \Log::info('End - SysConfigActionEvent');
    }
}
