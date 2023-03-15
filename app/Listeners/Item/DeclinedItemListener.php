<?php

namespace App\Listeners\Item;

use App\Events\Item\DeclinedItemEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Http\Repositories\ItemRepository;
use Illuminate\Support\Facades\Mail;
use App\Events\ItemHistoryEvent;

class DeclinedItemListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $itemRepository;
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Handle the event.
     *
     * @param  DeclinedItemEvent  $event
     * @return void
     */
    public function handle(DeclinedItemEvent $event)
    {
        \Log::info('Start - DeclinedItemEvent');

        $item_id = $event->item_id;
        \Log::info('Item ID : '.$item_id);        
        
        $payload = [
            'status'=>Item::_DECLINED_,
            'declined_date' => date('Y-m-d H:i:s')
        ];
        $result = $this->itemRepository->update($item_id, $payload, true, 'Declined');

        $item = Item::find($item_id);
        if($item && isset($item->customer)){
            $customer = $item->customer;
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\Declined($item));

            //for Item History
            $item_history = [
                'item_id' => $item_id,
                'customer_id' => $item->customer_id,
                'auction_id' => null,
                'item_lifecycle_id' => null,
                'price' => null,
                'type' => 'declined',
                'status' => Item::_DECLINED_,
                'entered_date' => date('Y-m-d H:i:s'),
            ];
            \Log::info('call ItemHistoryEvent - Declined');
            event( new ItemHistoryEvent($item_history) );
        }

        \Log::info('End - DeclinedItemEvent');
    }
}
