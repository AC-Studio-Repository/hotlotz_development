<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Events\Item\StorageFeeEvent;
use App\Helpers\NHelpers;

class SecondStorageFeeReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:second_storage_fee_reminder_email';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - SecondStorageFeeReminderEmail =======');
        \Log::channel('storageFeeReminderEmailLog')->info('======= Start - SecondStorageFeeReminderEmail =======');


        $items = Item::whereIn('items.status',[Item::_UNSOLD_, Item::_STORAGE_, Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_])
                ->where('items.tag', '!=', 'dispatched')
                ->where('items.storage_flag','C1')
                ->whereNotNull('items.storage_email1_date')
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
                        // \Log::channel('storageFeeReminderEmailLog')->info('Item ID : '.$item->id);

                        $total_period = $item->period + $item->second_period;
                        $end_day_free_of_charge = date('Y-m-d', strtotime('+'.$total_period.' day', strtotime($startDate) ));
                        \Log::channel('storageFeeReminderEmailLog')->info('end_day_free_of_charge : '.$end_day_free_of_charge);

                        if( $end_day_free_of_charge == date('Y-m-d')  ){
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
                    event( new StorageFeeEvent($customer_id, $items, 'second') );

                    $item_data = [
                        'storage_flag' => 'C2', //complete 2nd storage email send
                        'storage_email2_date' => date('Y-m-d H:i:s'),
                    ];
                    Item::whereIn('id',$item_ids)->update($item_data + NHelpers::updated_at_by());
                }
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - SecondStorageFeeReminderEmail =======');
        \Log::channel('storageFeeReminderEmailLog')->info('======= End - SecondStorageFeeReminderEmail =======');
    }
}
