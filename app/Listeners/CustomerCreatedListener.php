<?php

namespace App\Listeners;

use App\Events\CustomerCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CustomerCreatedListener
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
     * @param  CustomerCreatedEvent  $event
     * @return void
     */
    public function handle(CustomerCreatedEvent $event)
    {
        \Log::info('New Customer is created.');
        \Log::info('New Customer : '.$event->customer);

        if($event->customer->has_agreement == 0 && $event->customer->created_by == 1){
            Mail::to($event->customer->email)
                ->send(new \App\Mail\User\Invite($event->customer));
        }

        if($event->customer->has_agreement == 1){
            Mail::to($event->customer->email)
                ->send(new \App\Mail\User\Activate($event->customer));
        }

    }
}
