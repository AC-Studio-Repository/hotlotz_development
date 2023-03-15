<?php

namespace App\Listeners\Item;

use App\Events\Item\SubmissionReceivedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SubmissionReceivedListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(SubmissionReceivedEvent $event)
    {
        \Log::info('New Submission is created.');
        \Log::info('Customer : '.$event->customer);
        \Log::info('Items : ',$event->items);

        Mail::to($event->customer->email)
            ->send(new \App\Mail\Item\SubmissionReceived($event->customer, $event->items));
    }
}
