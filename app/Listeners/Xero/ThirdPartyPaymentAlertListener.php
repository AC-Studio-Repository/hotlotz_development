<?php

namespace App\Listeners\Xero;

use App\ThirdPartyPaymentAlert;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Xero\ThirdPartyPaymentAlertEvent;

class ThirdPartyPaymentAlertListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ThirdPartyPaymentAlertEvent  $event
     * @return void
     */
    public function handle(ThirdPartyPaymentAlertEvent $event)
    {
        \Log::info('ThirdParty Payment Alert Event Started');
        \Log::info('======= Payload - ThirdParty Payment Alert Event '. print_r($event->payload, true) .'=======');

        $payload = $event->payload;

        try {
            $customer = Customer::find($payload['customer_id']);
            $payment_type = $payload['payment_method'];

            $stripe = new \Stripe\StripeClient(
                setting('services.stripe.secret')
            );
            $stripePayment = $stripe->paymentMethods->retrieve(
                $payment_type,
                []
            );
            $payload['payment_data'] = json_encode(
                $stripePayment,
                JSON_PRETTY_PRINT
            );

            if($stripePayment->billing_details->name != $customer->fullname || ($customer->legal_name != null && $stripePayment->billing_details->name != $customer->legal_name)){
                ThirdPartyPaymentAlert::create($payload);
            }

        } catch (\throwable $e) {
            \Log::error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }

        \Log::info('ThirdParty Payment Alert Event Ended');

    }
}
