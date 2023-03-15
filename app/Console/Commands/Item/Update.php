<?php

namespace App\Console\Commands\Item;

use App\Helpers\NHelpers;
use App\Jobs\LotDeleteJob;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:update
                            {--id= : Item ID.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $id = $this->option("id");
    }

    public function itemLifecycleUpdate($item_id)
    {
        DB::beginTransaction();
        try {
            \Log::info('item_id : '.print_r($item_id, true));

            $old_item = Item::find($item_id);
            if($old_item->permission_to_sell != 'Y'){

                $payload = [
                    'valuer_id' => 1,
                    'vat_tax_rate' => 0.00,
                    'low_estimate' => 100,
                    'high_estimate' => 200,
                    'is_reserve' => isset($request->is_reserve)?'Y':'N',
                    'reserve' => isset($request->reserve)?$request->reserve:null,
                    'is_hotlotz_own_stock' => isset($request->is_hotlotz_own_stock)?'Y':'N',
                    'supplier' => isset($request->supplier)?$request->supplier:null,
                    'purchase_cost' => isset($request->purchase_cost)?$request->purchase_cost:null,
                    'supplier_gst' => isset($request->supplier_gst)?$request->supplier_gst:null,
                ];

                if (isset($request->lifecycle_id)) {
                    $payload['lifecycle_id'] = $request->lifecycle_id;

                    if ($old_item->lifecycle_id > 0 && $old_item->lifecycle_id != $request->lifecycle_id) {
                        ItemLifecycle::where('item_id', $item_id)->forceDelete();
                        $del_lot_ids = AuctionItem::where('item_id', $item_id)->pluck('lot_id')->all();

                        foreach ($del_lot_ids as $del_lot_id) {
                            if ($del_lot_id != null) {
                                // event(new GAPDeleteLotEvent($del_lot_id));
                                \Log::channel('gapLog')->info('dispatch LotDeleteJob');
                                LotDeleteJob::dispatch($del_lot_id);
                            }
                        }
                        AuctionItem::where('item_id', $item_id)->forceDelete();
                    }
                }

                $result = $this->itemRepository->update($item_id, $payload, true, 'Lifecycle');

                $item = Item::find($item_id);
                $existing_ids = ItemLifecycle::where('item_id', $item_id)->pluck('id')->all();

                // dd($request->all());
                if (isset($request->type) && count($request->type) > 0) {
                    for ($i=0; $i < count($request->type); $i++) {
                        if ($request->type[$i] == 'auction') {
                            $reference_id = $request->auction_id[$i];

                            $exist_auction_count = AuctionItem::where('item_id', $item_id)->where('auction_id', $request->auction_id[$i])->count();

                            if ($exist_auction_count <= 0) {
                                $auction = Auction::find($request->auction_id[$i]);

                                $auction_item = [
                                    'auction_id' => $request->auction_id[$i],
                                    'item_id' => $item_id,
                                    'status' => null,
                                    'lot_id' => null,
                                    'lot_number' => null,
                                    'sequence_number' => null,
                                    'end_time_utc' => $auction->timed_first_lot_ends,
                                    'starting_bid' => $request->price[$i],
                                ];

                                AuctionItem::insert($auction_item + NHelpers::created_updated_at_by());
                            }
                        } else {
                            $reference_id = isset($request->hid_marketplace[$i])?$request->hid_marketplace[$i]:'';
                        }

                        $second_period = null;
                        $is_indefinite_period = null;
                        if ($request->type[$i] == 'storage') {
                            $second_period = $request->second_period;
                            $is_indefinite_period = isset($request->is_indefinite_period)?'Y':'N';
                        }

                        $item_lifecycle = [];
                        $item_lifecycle['item_id'] = $item_id;
                        $item_lifecycle['type'] = $request->type[$i];
                        $item_lifecycle['price'] = $request->price[$i];
                        $item_lifecycle['reference_id'] = $reference_id;
                        $item_lifecycle['period'] = $request->period[$i];
                        $item_lifecycle['second_period'] = $second_period;
                        $item_lifecycle['is_indefinite_period'] = $is_indefinite_period;


                        if (in_array($request->item_lifecycle_id[$i], $existing_ids) && $request->item_lifecycle_id[$i] != 0) {
                            ItemLifecycle::where('id', $request->item_lifecycle_id[$i])->update($item_lifecycle + NHelpers::updated_at_by());
                        } else {
                            ItemLifecycle::insert($item_lifecycle + NHelpers::created_updated_at_by());
                        }
                    }
                }

                $existing_il_auction_ids = ItemLifecycle::where('item_id', $item_id)->where('type', 'auction')->pluck('reference_id')->all();

                ## Delete Lot
                $delete_auctionitems = AuctionItem::where('item_id', $item_id)->whereNotIn('auction_id', $existing_il_auction_ids)->pluck('lot_id', 'auction_id')->all();

                foreach ($delete_auctionitems as $del_auction_id => $del_lot_id) {
                    if ($del_lot_id != null) {
                        // event(new GAPDeleteLotEvent($del_lot_id));
                        \Log::channel('gapLog')->info('dispatch LotDeleteJob');
                        LotDeleteJob::dispatch($del_lot_id);
                    }

                    AuctionItem::where('item_id', $item_id)->where('auction_id', $del_auction_id)->forceDelete();
                }

                flash()->success(__('Lifecycle of :name has been updated', ['name' => Item::getNameById($item_id)]));

                DB::commit();
                return redirect(route('item.items.show', ['item' => Item::find($item_id) ]));
            }else{
                DB::rollback();
                flash()->error(__('Error::msg', ['msg' => $old_item->name.'\'s Lifecycle update Failed. This item has already permission to sell.']));
                return redirect(route('item.items.show', ['item' => Item::find($item_id) ]));
            }
        } catch (Exception $e) {
            DB::rollback();
            flash()->error(__('Error::msg', ['msg' => $e]));
            return redirect()->back()->withInput();
        }
    }
}
