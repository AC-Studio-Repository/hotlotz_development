<?php

namespace App\Listeners\Mailchimp;

use App\Events\Mailchimp\AddOrUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Customer\Models\Customer;

class AddOrUpdateListener
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
     * @param  AddOrUpdateEvent  $event
     * @return void
     */
    public function handle(AddOrUpdateEvent $event)
    {
        \Log::channel('mailchimpLog')->info('Start - AddOrUpdateEvent');

        $customer_id = $event->customer_id;
        \Log::channel('mailchimpLog')->info('customer_id : '.$customer_id);

        $customer = Customer::find($customer_id);

        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => setting('services.mailchimp.api'),
            'server' => setting('services.mailchimp.server_prefix')
        ]);

        $list_id = setting('services.mailchimp.list_id');

        try{

        if($customer && $customer->phone != '' &&  $customer->phone != null){
            $response = $mailchimp->lists->setListMember($list_id, md5(strtolower($customer->email)), [
                "email_address" => $customer->email,
                "status" => $customer->exclude_marketing_material == 1 ? "subscribed" : "unsubscribed",
                "merge_fields" => [
                "FNAME" => $customer->firstname ?? 'N/A',
                "LNAME" => $customer->lastname ?? 'N/A',
                "PHONE" => $customer->dialling_code . $customer->phone,
                ]
           ]);

           $mailchimp->lists->updateListMemberTags($list_id, md5(strtolower($customer->email)), ["tags" => $this->tags($customer)]);

           $customer->mailchimp_sync = 1;
           $customer->save();
        }

        \Log::channel('mailchimpLog')->info('Success - AddOrUpdateEvent');

        } catch (\Exception $e) {
            \Log::channel('mailchimpLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }

        \Log::channel('mailchimpLog')->info('End - AddOrUpdateEvent');
    }

    protected function tags($customer)
    {
        $tags[] = [
            "name" => "HotLotz client",
            "status" => "active"
        ];

        return $tags;


        $tags = [];
        if($customer->marketing_auction == 1){
            $tags[] = [
                "name" => "Auction Updates",
                "status" => "active"
            ];
        }else{
            $tags[] = [
                "name" => "Auction Updates",
                "status" => "inactive"
            ];
        }
        if($customer->marketing_marketplace == 1){
            $tags[] = [
                "name" => "Marketplace Updates",
                "status" => "active"
            ];
        }else{
            $tags[] = [
                "name" => "Marketplace Updates",
                "status" => "inactive"
            ];
        }
        if($customer->marketing_chk_events == 1){
            $tags[] = [
                "name" => "Events",
                "status" => "active"
            ];
        }else{
            $tags[] = [
                "name" => "Events",
                "status" => "inactive"
            ];
        }
        if($customer->marketing_chk_congsignment_valuation == 1){
            $tags[] = [
                "name" => "Consignment & Valuation",
                "status" => "active"
            ];
        }else{
            $tags[] = [
                "name" => "Consignment & Valuation",
                "status" => "inactive"
            ];
        }
        if($customer->marketing_hotlotz_quarterly == 1){
            $tags[] = [
                "name" => "Hotlotz Quarterly Newsletter",
                "status" => "active"
            ];
        }else{
            $tags[] = [
                "name" => "Hotlotz Quarterly Newsletter",
                "status" => "inactive"
            ];
        }

        return $tags;
    }
}
