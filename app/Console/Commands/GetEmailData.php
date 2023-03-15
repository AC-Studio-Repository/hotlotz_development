<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemHistory;
use App\Modules\Auction\Models\Auction;
use App\Modules\Customer\Models\Customer;
use App\Events\Item\ConsignmentUpdateEvent;


class GetEmailData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:get_email_data';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - GetEmailData Command =======');
        \Log::channel('emailLog')->info('======= Start - GetEmailData Command =======');

        $prev_date = date('Y-m-d',strtotime("-1 days"));
        $today_date = date('Y-m-d');
        $from = $prev_date . " 12:00:00";
        $to = $today_date . " 12:00:00";

        $item_histories = ItemHistory::whereNull('email_flag')
            ->where('entered_date', '>=', $from)
            ->where('entered_date', '<', $to)
            ->get();

        \Log::channel('emailLog')->info('Count of item_histories : '.count($item_histories));

        $data = [];
        foreach ($item_histories as $key => $item_history) {
            $item = Item::find($item_history->item_id);
            $auction = Auction::find($item_history->auction_id);

            $ar_statuses = [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_UNSOLD_];
            if( $item_history->type == 'auction' && in_array($item_history->status, $ar_statuses)){

                $data[$item_history->customer_id]['auction_results'][] = [
                    'id' => $item_history->id,
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'auction_id' => $item_history->auction_id,
                    'auction_name' => isset($auction)?$auction->title:null,
                    'buyer_id' => $item_history->buyer_id,
                    'price' => $item_history->price,
                    'sold_price' => $item->sold_price,
                    'type' => $item_history->type,
                    'status' => $item_history->status,
                ];
            }

            if( $item_history->type == 'marketplace' && in_array($item_history->status, $ar_statuses)){

                $data[$item_history->customer_id]['mp_results'][] = [
                    'id' => $item_history->id,
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'auction_id' => null,
                    'auction_name' => null,
                    'buyer_id' => $item_history->buyer_id,
                    'price' => $item_history->price,
                    'sold_price' => $item->sold_price,
                    'type' => $item_history->type,
                    'status' => $item_history->status,
                ];
            }

            $lifecycle_statues = [Item::_AUCTION_, Item::_MARKETPLACE_, Item::_CLEARANCE_, Item::_STORAGE_];
            if($item_history->type == 'lifecycle' && in_array($item_history->status, $lifecycle_statues)){

                $data[$item_history->customer_id]['notifications'][] = [
                    'id' => $item_history->id,
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'auction_id' => $item_history->auction_id,
                    'auction_name' => isset($auction)?$auction->title:null,
                    'price' => $item_history->price,
                    'type' => $item_history->type,
                    'status' => $item_history->status,
                ];
            }
        }

        foreach ($data as $customer_id => $consignment_update_data) {
            \Log::channel('emailLog')->info('call ConsignmentUpdateEvent');
            event( new ConsignmentUpdateEvent($customer_id, $consignment_update_data) );
        }


        $this->info(date('Y-m-d H:i:s').' ======= End - GetEmailData Command =======');
        \Log::channel('emailLog')->info('======= End - GetEmailData Command =======');
    }
}
