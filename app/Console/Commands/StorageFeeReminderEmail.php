<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Events\Item\StorageFeeEvent;
use App\Helpers\NHelpers;

class StorageFeeReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:storage_fee_reminder_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminder Email for Storage Fee';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $itemRepository;
    public function __construct(ItemRepository $itemRepository)
    {
        parent::__construct();
        $this->itemRepository = $itemRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - StorageFeeReminderEmail =======');
        \Log::channel('storageFeeReminderEmailLog')->info('======= Start - StorageFeeReminderEmail =======');


        $items = Item::whereIn('items.status',[Item::_UNSOLD_, Item::_STORAGE_, Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_])
                ->where('items.tag', '!=', 'dispatched')
                ->whereNull('items.storage_flag')
                ->whereNull('items.storage_email1_date')
                ->join('item_lifecycles', 'item_lifecycles.item_id', 'items.id')
                ->where('item_lifecycles.type','storage')
                ->where('item_lifecycles.action',ItemLifecycle::_PROCESSING_)
                ->where('item_lifecycles.is_indefinite_period','!=','Y')
                ->whereNull('item_lifecycles.deleted_at')
                ->select(
                    'items.id','items.name','items.status as item_status','items.lifecycle_status','items.storage_date','items.storage_flag','items.customer_id','items.buyer_id',
                    'item_lifecycles.price', 'item_lifecycles.period', 'item_lifecycles.second_period', 'item_lifecycles.entered_date'
                )
                ->get();

        $this->info('Item count '.count($items));
        \Log::channel('storageFeeReminderEmailLog')->info('Item count '.count($items));

        $data = [];

        if($items != null && count($items) > 0){
            foreach ($items as $key => $item) {
                if($item->storage_date != null || $item->entered_date != null){

                    $startDate = null;
                    if($item->storage_date != null)
                    {
                        $startDate = $item->storage_date;
                    }

                    if($item->storage_date == null && $item->entered_date != null)
                    {
                        $startDate = $item->entered_date;
                    }
                    \Log::channel('storageFeeReminderEmailLog')->info('startDate : '.$startDate);
                    
                    if($startDate != null){
                        $end_day_of_first_period = date('Y-m-d', strtotime('+'.$item->period.' day', strtotime($startDate) ));
                        \Log::channel('storageFeeReminderEmailLog')->info('end_day_of_first_period : '.$end_day_of_first_period);

                        if( $end_day_of_first_period == date('Y-m-d')  ){

                            $item_ids[] = $item->id;

                            $email_data = [
                                'item_id' => $item->item_id,
                                'item_name' => $item->name,
                                'price' => $item->price,
                            ];

                            if($item->customer_id != null && in_array($item->item_status,[Item::_UNSOLD_, Item::_STORAGE_, Item::_WITHDRAWN_]) ){
                                $data[$item->customer_id][] = $email_data;
                            }

                            if($item->buyer_id != null && in_array($item->item_status,[Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) ){
                                $data[$item->buyer_id][] = $email_data;
                            }
                        }
                    }
                }
            }

            if(isset($data) && count($data) > 0){
                foreach ($data as $customer_id => $items) {

                    \Log::channel('storageFeeReminderEmailLog')->info('call StorageFeeEvent');
                    event( new StorageFeeEvent($customer_id, $items, 'first') );

                    $item_data = [
                        'storage_flag' => 'C1', //complete 1st storage email send
                        'storage_email1_date' => date('Y-m-d H:i:s'),
                    ];
                    Item::whereIn('id',$item_ids)->update($item_data + NHelpers::updated_at_by());
                }
            }
        }        

        $this->info(date('Y-m-d H:i:s').' ======= End - StorageFeeReminderEmail =======');
        \Log::channel('storageFeeReminderEmailLog')->info('======= End - StorageFeeReminderEmail =======');
    }
}
