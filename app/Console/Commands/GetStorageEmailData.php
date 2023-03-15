<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemHistory;
use App\Modules\Customer\Models\Customer;
use App\Events\Item\StorageEvent;
use App\Events\Item\StorageFeeEvent;
use App\Helpers\NHelpers;


class GetStorageEmailData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:get_storage_email_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Email Data from Item History Table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - GetStorageEmailData Command =======');
        \Log::channel('storageFeeReminderEmailLog')->info('======= Start - GetStorageEmailData Command =======');

        $today_date = date('Y-m-d');

        $item_histories = ItemHistory::where('type','lifecycle')->where('status', Item::_STORAGE_)->whereDate('entered_date', $today_date)->get();
        \Log::channel('storageFeeReminderEmailLog')->info('Count of item_histories : '.count($item_histories));

        $data = [];
        foreach ($item_histories as $key => $item_history) {
            $item = Item::find($item_history->item_id);

            if(isset($item)){

                $email_data = [
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'price' => $item_history->price,
                ];

                if($item_history->customer_id != null){
                    $data[$item_history->customer_id][] = $email_data;
                }
            }
        }
        // \Log::channel('storageFeeReminderEmailLog')->info('data : '.print_r($data,true));


        foreach ($data as $customer_id => $items) {

            \Log::channel('storageFeeReminderEmailLog')->info('call StorageEvent');
            event( new StorageEvent($customer_id, $items) );
            
        }


        $this->info(date('Y-m-d H:i:s').' ======= End - GetStorageEmailData Command =======');
        \Log::channel('storageFeeReminderEmailLog')->info('======= End - GetStorageEmailData Command =======');
    }
}