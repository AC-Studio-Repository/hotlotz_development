<?php

namespace App\Listeners;

use App\Events\EmailTemplateActionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailTemplateActionListener
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    // public $tries = 100;
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
     * @param  EmailTemplateActionEvent  $event
     * @return void
     */
    public function handle(EmailTemplateActionEvent $event)
    {
        \Log::info('Start - EmailTemplateActionEvent');
        
        $data = $event->data;
        \Log::info('Email Data : '.print_r($data, true));

        \Log::info('"'.$data['title'].'" Template is '.$data['action'].'.');

        \Log::info('End - EmailTemplateActionEvent');
    }
}
