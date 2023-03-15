<?php

namespace App\Modules\ItemLifecycleTrigger\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\ItemLifecycleTrigger\Models\ItemLifecycleTrigger;
use DB;
use App\Helpers\NHelpers;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;
use App\Events\ItemLifecycleStartEvent;
use App\Events\ItemLifcycleNextStageChangeEvent;
use App\Events\Item\WithdrawItemEvent;
use App\Jobs\LifecycleStart;

class ItemLifecycleTriggerController extends Controller
{
    public function __construct(){
        //
    }

    /**
     * Displays the category index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $item = app(Item::class);
        // $item_lists = Item::whereNull('items.deleted_at')
        //         ->whereIn('items.status',[Item::_PENDING_, Item::_IN_AUCTION_, Item::_IN_MARKETPLACE_])
        //         ->where('items.permission_to_sell','Y')
        //         ->pluck('name','id')
        //         ->all();
        // dd($item_lists);

        $event_actions = ['lifecycle_start'=>'Lifecycle Start', 'change_next_stage' => 'Change Next Stage', 'withdraw'=>'Withdraw', 'cataloguing_approval_reset'=>'Cataloguing Approval Reset'];

        $data = [
            'item' => $item,
            // 'item_lists' => $item_lists,
            'event_actions' => $event_actions,
        ];
        return view('item_lifecycle_trigger::index', $data);
    }

    public function lifecycle(Request $request)
    {
        try {
            $event_action = $request->event_action;
            $item_id = '';
            $item = Item::where('item_number',$request->item_number)->first();

            if($item && isset($item) && $item != null && !is_null($item) && !empty($item) && in_array($item->status,[Item::_PENDING_, Item::_IN_AUCTION_, Item::_IN_MARKETPLACE_]) && $item->permission_to_sell == 'Y' ) {
                $item_id = $item->id;
            }

            if($item_id != '') {
                $newitemlifecycle = ItemLifecycle::where('item_lifecycles.item_id',$item_id)
                        ->whereNull('item_lifecycles.action')
                        ->select('item_lifecycles.*')
                        ->orderBy('item_lifecycles.id')
                        ->first();
                // dd($newitemlifecycle);
               
                if($event_action == 'lifecycle_start' && $item && isset($item) && $item != null && !is_null($item) && !empty($item) && $item->is_cataloguing_approved === 'Y' && $item->permission_to_sell === 'Y' && $item->status === Item::_PENDING_){
            
                    \Log::info('Item Lifecycle Trigger - dispatch LifecycleStart Job '.$item_id);
                    LifecycleStart::dispatch($item_id);
                }

                // if($event_action == 'lifecycle_start' && $newitemlifecycle->type != 'privatesale'){

                //     $status_data = Item::checkLifecycleStatus($newitemlifecycle);

                //     $item_history = [
                //         'item_id' => $item_id,
                //         'customer_id' => $newitemlifecycle->customer_id,
                //         'buyer_id' => null,
                //         'auction_id' => $newitemlifecycle->reference_id,
                //         'item_lifecycle_id' => $newitemlifecycle->id,
                //         'price' => $newitemlifecycle->price,
                //         'type' => 'lifecycle',
                //         'status' => $status_data['lifecycle_status'],
                //         'entered_date' => date('Y-m-d H:i:s'),
                //     ];

                //     event( new ItemLifecycleStartEvent($newitemlifecycle->id, $item_id, $newitemlifecycle, $status_data['lifecycle_status'], $item_history) );
                // }

                if($newitemlifecycle && isset($newitemlifecycle) && $newitemlifecycle != null && !is_null($newitemlifecycle) && !empty($newitemlifecycle) && $event_action == 'change_next_stage'){

                    $olditemlifecycle = ItemLifecycle::where('item_id',$item_id)
                                        ->where('action',ItemLifecycle::_PROCESSING_)
                                        ->first();

                    if(isset($olditemlifecycle)){
                        event( new ItemLifcycleNextStageChangeEvent($item_id, $olditemlifecycle->id) );
                    }
                }

                if($event_action == 'withdraw'){
                    event( new WithdrawItemEvent($item_id) );
                }
               
                if($event_action == 'cataloguing_approval_reset'){
            
                    \Log::info('Item Lifecycle Trigger - Cataloguing Approval Reset '.$request->item_number);
                    Item::where('id',$item_id)->update(['is_cataloguing_approved'=>'N']);
                }

                flash()->success(__('Successfully done Item Lifecycle Trigger Change'));
                return redirect( route('item_lifecycle_trigger.itemlifecycletriggers.index') );
            }
            
            flash()->error(__('Failed Item Lifecycle Trigger Change'));
            return redirect( route('item_lifecycle_trigger.itemlifecycletriggers.index') );

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }
    }
}
