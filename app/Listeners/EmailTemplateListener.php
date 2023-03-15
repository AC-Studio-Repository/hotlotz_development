<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\EmailTemplateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailTemplateMail;

class EmailTemplateListener implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 100;

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    // public function retryUntil()
    // {
    //     return now()->addSeconds(60);
    // }

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
     * @param  EmailTemplateEvent  $event
     * @return void
     */
    public function handle(EmailTemplateEvent $event)
    {
        \Log::info('Start - EmailTemplateEvent');

        try {
            $data = $event->data;
            \Log::info('Email Data : '.print_r($data, true));

            Mail::to($data['to_email'])->send(new EmailTemplateMail($data));

            if (Mail::failures()) {
                \Log::info('Sorry! Please try again latter');
            } else {
                \Log::info('Great! Successfully send in your mail');
            }
        } catch (\Exception $e) {
            \Log::error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::info('End - EmailTemplateEvent');
    }


    public function failed(EmailTemplateEvent $event, \Exception $exception)
    {
        \Log::error('======= Failed - EmailTemplateEvent '. print_r($event->data, true) .'=======');
    }
}
