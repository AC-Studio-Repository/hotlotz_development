<?php

namespace App\Listeners\Client;

use App\Events\Client\ForgetPasswordEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ForgetPasswordListener
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
     * @param  ForgetPasswordEvent  $event
     * @return void
     */
    public function handle(ForgetPasswordEvent $event)
    {
        \Log::channel('emailLog')->info('Start - ForgetPasswordEvent');

        $customer_id = $event->customer_id;
        \Log::channel('emailLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        if($customer){
            Mail::to($customer->email)
                ->send(new \App\Mail\User\ForgetPassword($customer));
        }

        \Log::channel('emailLog')->info('Start - ForgetPasswordEvent');
    }
}
