<?php

namespace App\Listeners\Mailchimp;

use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Mailchimp\SubscriberAddOrUpdateEvent;

class SubscriberAddOrUpdateListener
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
     * @param  SubscriberAddOrUpdateEvent  $event
     * @return void
     */
    public function handle(SubscriberAddOrUpdateEvent $event)
    {
        \Log::channel('mailchimpLog')->info('Start - SubscriberAddOrUpdateEvent');

        $id = $event->id;
        \Log::channel('mailchimpLog')->info('Subscriber ID : '.$id);

        $subscriber = Subscriber::find($id);

        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => setting('services.mailchimp.api'),
            'server' => setting('services.mailchimp.server_prefix')
        ]);

        $list_id = setting('services.mailchimp.list_id');

        try{

        if($subscriber){
            $response = $mailchimp->lists->setListMember($list_id, md5(strtolower($subscriber->email)), [
                "email_address" => $subscriber->email,
                "status" => "subscribed",
                "merge_fields" => [
                "FNAME" => $subscriber->email ?? 'N/A',
                "LNAME" => $subscriber->email ?? 'N/A',
                "PHONE" => '000000000',
                ]
           ]);

           $mailchimp->lists->updateListMemberTags($list_id, md5(strtolower($subscriber->email)), ["tags" => $this->tags($subscriber)]);
        }

        \Log::channel('mailchimpLog')->info('Success - SubscriberAddOrUpdateEvent');

        } catch (\Exception $e) {
            \Log::channel('mailchimpLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }

        \Log::channel('mailchimpLog')->info('End - SubscriberAddOrUpdateEvent');
    }

    protected function tags($subscriber)
    {
        $tags[] = [
            "name" => "Hotlotz Subscriber",
            "status" => "active"
        ];

        return $tags;

        return [];
    }
}
