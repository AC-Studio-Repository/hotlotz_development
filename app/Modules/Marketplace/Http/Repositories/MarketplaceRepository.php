<?php

namespace App\Modules\Marketplace\Http\Repositories;

use App\Modules\Marketplace\Models\Marketplace;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemLifecycle;

class MarketplaceRepository
{
    public function __construct(Marketplace $marketplace)
    {
        $this->marketplace = $marketplace;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0)
    {
        return $this->marketplace
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        if ($withTrash) {
                            return $query->withEagerTrashed($eagerLoad);
                        } else {
                            return $query->with($eagerLoad);
                        }
                    })
                    ->when($paginateCount, function ($query, $role) use ($paginateCount) {
                        return $query->paginate($paginateCount);
                    }, function ($query) {
                        return $query->get();
                    });
    }

    public function show($column, $value, $eagerLoad = [], $withTrash = false, $returnMany = false)
    {
        return $this->marketplace
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        if ($withTrash) {
                            return $query->withEagerTrashed($eagerLoad);
                        } else {
                            return $query->with($eagerLoad);
                        }
                    })
                    ->where($column, $value)
                    ->when($returnMany, function ($query, $role) {
                        return $query->get();
                    }, function ($query) {
                        return $query->first();
                    });
    }

    public function create($payload)
    {
        return $this->marketplace->create($payload);
    }

    public function update($id, $payload, $withTrash = false)
    {
        return $this->marketplace
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id)
    {
        return $this->marketplace->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1)
    { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->marketplace->destroy($id);
        } else {
            return $this->marketplace->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id)
    {
        return $this->marketplace->withTrashed()->find($id)->restore();
    }

    public function generateLabel($item_ids)
    {
        // $items = Item::where('items.status', Item::_IN_MARKETPLACE_)
        //         ->join('item_lifecycles', 'item_lifecycles.item_id', 'items.id')
        //         ->where('item_lifecycles.action', ItemLifecycle::_PROCESSING_)
        //         ->whereNull('item_lifecycles.deleted_at')
        //         ->select('items.*', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.entered_date')
        //         ->orderBy('item_lifecycles.entered_date', 'desc');

        $today = date('Y-m-d');
        $last_week = date("Y-m-d", strtotime("-7 days"));

        $items = Item::where('items.status', Item::_IN_MARKETPLACE_)
                ->where( function($query) use ($last_week, $today) {
                    $query->where( function($query2) use ($last_week, $today) {
                        $query2->where('items.lifecycle_status', 'Marketplace');
                        $query2->whereDate('entered_marketplace_date','>=', $last_week);
                        $query2->whereDate('entered_marketplace_date','<=', $today);
                    });
                    $query->orWhere( function($query2) use ($last_week, $today) {
                        $query2->where('items.lifecycle_status', Item::_CLEARANCE_);
                        $query2->whereDate('entered_clearance_date','>=', $last_week);
                        $query2->whereDate('entered_clearance_date','<=', $today);
                    });
                })
                ->select('items.*')
                ->orderBy('entered_marketplace_date', 'desc')
                ->orderBy('entered_clearance_date', 'desc');

        if($item_ids != null){
            $items = $items->whereIn('items.id', $item_ids)->get();
        }else{
            $items = $items->get();
        }
        // dd($items->toArray());

        $data = [];
        if (count($items)) {
            foreach ($items as $key => $item) {
                $itemlifecycle = ItemLifecycle::where('item_id',$item->id)->whereIn('type', ['marketplace','clearance'])->where('action', ItemLifecycle::_PROCESSING_)->first();
                $buy_now_price = 0;
                if($itemlifecycle != null){
                    $buy_now_price = $itemlifecycle->price;
                }

                $data[] = [
                    'title' => 'Marketplace',
                    'category_name' => isset($item->category)?$item->category->name:'',
                    'lot_number' => $item->item_number,
                    'item_title' => $item->name,
                    'price' => '$'.number_format($buy_now_price).' SGD',
                    'item_number' => $item->item_number,
                ];
            }
        }

        return $data;
    }

    public function generateBuyerLabel($item_ids)
    {
        // $items = Item::whereIn('items.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
        //         ->whereIn('items.lifecycle_status', [Item::_MARKETPLACE_,Item::_CLEARANCE_])
        //         ->join('item_lifecycles', 'item_lifecycles.item_id', 'items.id')
        //         ->whereIn('item_lifecycles.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
        //         ->whereRaw('item_lifecycles.type = LOWER(items.lifecycle_status)')
        //         ->select('items.*', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.entered_date')
        //         ->orderBy('items.sold_date', 'desc');

        $itemLifecycles = ItemLifecycle::whereIn('item_lifecycles.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
                ->whereIn('item_lifecycles.type', ['marketplace','clearance'])
                ->select('item_id')
                ->groupBy('item_id');

        $items = Item::whereIn('items.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
                ->whereIn('items.lifecycle_status', [Item::_MARKETPLACE_,Item::_CLEARANCE_])
                ->join('item_lifecycles', 'item_lifecycles.item_id', 'items.id')
                ->join('customers', 'customers.id', 'items.buyer_id')
                ->whereIn('item_lifecycles.status', [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
                ->whereRaw('item_lifecycles.type = LOWER(items.lifecycle_status)')
                ->select('items.*', 'item_lifecycles.type', 'item_lifecycles.price', 'item_lifecycles.entered_date')
                ->orderBy('items.sold_date', 'desc');

        if($item_ids != null){
            $items = $items->whereIn('items.id', $item_ids)->get();
        }else{
            $items = $items->get();
        }
        // dd($items->toArray());

        $data = [];
        if (count($items)) {
            foreach ($items as $key => $item) {
                $name = $item->name;
                // $first_entered_date = date_format(date_create($item->entered_date), 'jS F Y');
                // $second_entered_date = date_format(date_create($item->entered_date), 'h:i A');

                $data[] = [
                    // 'title' => \Illuminate\Support\Str::limit($name.' treatment '.$first_entered_date.' | from '.$second_entered_date.', '.$item->long_description, 114, $end='...'),
                    'title' => $item->name,
                    'customer_ref' => isset($item->ref_no)?$item->ref_no:'',
                    'customer_fullname' => isset($item->fullname) ? $item->fullname: '',
                    'sale_date' => date_format(date_create($item->sold_date), 'Y/m/d'),
                    'lot_number' => $item->item_number,
                    'item_number' => $item->item_number,
                ];
            }
        }

        return $data;
    }

    public function generateLabelMpAll($item_ids)
    {
        $items = Item::where('items.status', Item::_IN_MARKETPLACE_)
                ->select('items.*')
                ->orderBy('entered_marketplace_date', 'desc')
                ->orderBy('entered_clearance_date', 'desc');

        if($item_ids != null){
            $items = $items->whereIn('items.id', $item_ids)->get();
        }else{
            $items = $items->get();
        }
        // dd($items->toArray());

        $data = [];
        if (count($items)) {
            foreach ($items as $key => $item) {
                $itemlifecycle = ItemLifecycle::where('item_id',$item->id)->whereIn('type', ['marketplace','clearance'])->where('action', ItemLifecycle::_PROCESSING_)->first();
                $buy_now_price = 0;
                if($itemlifecycle != null){
                    $buy_now_price = $itemlifecycle->price;
                }

                $data[] = [
                    'title' => 'Marketplace',
                    'category_name' => isset($item->category)?$item->category->name:'',
                    'lot_number' => $item->item_number,
                    'item_title' => $item->name,
                    'price' => '$'.number_format($buy_now_price).' SGD',
                    'item_number' => $item->item_number,
                ];
            }
        }
        // dd($data);

        return $data;
    }
}
