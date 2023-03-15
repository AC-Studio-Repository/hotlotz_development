<?php

namespace App\Listeners\Item;

use App\Helpers\NHelpers;
use App\Events\Item\WithdrawItemEvent;
use App\Modules\Item\Models\Item;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\ItemLifecycle;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Xero\XeroWithdrawInvoiceEvent;
use App\Modules\Item\Http\Repositories\ItemRepository;
use Illuminate\Support\Facades\Mail;
use App\Events\ItemHistoryEvent;

class WithdrawItemListener
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
     * @param  WithdrawItemEvent  $event
     * @return void
     */
    public function handle(WithdrawItemEvent $event)
    {
        \Log::info('Start - WithdrawItemEvent');

        $item_id = $event->item_id;
        \Log::info('item_id : '.$item_id);

        $olditem = Item::find($item_id);
        $lifecycle_status = ($olditem) ? $olditem->lifecycle_status:null;

        $today = date('Y-m-d H:i:s');

        $item_data = [
            'status'=>Item::_WITHDRAWN_,
            // 'lifecycle_status'=>Item::_STORAGE_, //command out by mct[4May2022]
            'withdrawn_date'=>$today,
            'storage_date'=>$today,
            'tag'=>'in_storage',
        ];
        $result = $this->itemRepository->update($item_id, $item_data, true, Item::_WITHDRAWN_);

        ItemLifecycle::where('item_id', $item_id)->where('type', '!=', 'storage')->update(['action'=>ItemLifecycle::_FINISHED_] + NHelpers::updated_at_by());

        ItemLifecycle::where('item_id', $item_id)->where('type', 'storage')->update(['action'=>ItemLifecycle::_PROCESSING_, 'entered_date'=>$today, 'withdrawn_date'=>$today] + NHelpers::updated_at_by());

        $item = Item::find($item_id);

        $storage_item_lifecycle = ItemLifecycle::where('type','storage')->where('item_id',$item_id)->first();

        //for Email Schedule
        $item_history = [
            'item_id' => $item_id,
            'customer_id' => $item->customer_id,
            'auction_id' => null,
            'item_lifecycle_id' => $storage_item_lifecycle->id,
            'price' => $storage_item_lifecycle->price,
            'type' => 'lifecycle',
            'status' => Item::_STORAGE_,
            'entered_date' => $today,
        ];
        \Log::info('call ItemHistoryEvent - enter into storage');
        event( new ItemHistoryEvent($item_history) );


        \Log::info('call XeroWithdrawInvoiceEvent');
        event(new XeroWithdrawInvoiceEvent($item_id, strtolower($lifecycle_status)));
        // event(new XeroWithdrawInvoiceEvent($item_id, 'marketplace'));//command out by mct[4May2022]


        if (isset($item->customer)) {
            $customer = $item->customer;
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\Withdraw($item));
        }

        \Log::info('End - WithdrawItemEvent');
    }
}
