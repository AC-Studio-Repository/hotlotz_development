<?php

namespace App\Modules\OrderSummary\Http\Repositories;

use App\Helpers\NHelpers;
use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\OrderSummary\Models\OrderSummary;

class OrderSummaryRepository
{
    public function __construct(OrderSummary $order_summary)
    {
        $this->order_summary = $order_summary;
    }

    public function all($column = null, $value = null, $eagerLoad = [], $withTrash = true, $paginateCount = 0, $filter = [])
    {
        return $this->order_summary
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
                    ->when($filter == [], function ($query) use ($filter) {
                        $query->where('status', '!=', 'complete');
                    })
                    ->when($filter != [], function ($query) use ($filter) {

                            if(isset($filter['search'])){

                                $filterString = NHelpers::getStringBetween($filter['search'], ' (', ')');
                                if($filterString != ""){
                                    $customerIds = Customer::where('ref_no', 'like', '%'.$filterString.'%')->pluck('id');
                                }else{
                                    $customerIds = Customer::where('fullname', 'like', '%'.$filter['search'].'%')->pluck('id');
                                }
                                $query->whereIn('customer_id', $customerIds);
                            }

                            if(isset($filter['status'])){
                                $query->where('status', $filter['status']);
                            }

                            if(isset($filter['orderType'])){
                                $query->where('type', $filter['orderType']);
                            }

                            if(isset($filter['customer'])){
                                $query->where('customer_id', $filter['customer']);
                            }

                            if(isset($filter['from'])){
                                if($filter['from'] != 'marketplace'){
                                    $auction_id = $filter['from'];
                                    $invoiceIds = CustomerInvoice::where('auction_id', $auction_id)->pluck('invoice_id');
                                    $query->whereIn('invoice_id', $invoiceIds);
                                }
                            }
                            return $query;
                    })
                    ->latest()
                    ->when($paginateCount, function ($query, $role) use ($paginateCount) {
                        return $query->paginate($paginateCount);
                    }, function ($query) {
                        return $query->get();
                    });
    }

    public function show($column, $value, $eagerLoad = [], $withTrash = false, $returnMany = false)
    {
        return $this->order_summary
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
        return $this->order_summary->create($payload);
    }

    public function update($id, $payload, $withTrash = false)
    {
        return $this->order_summary
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id)
    {
        return $this->order_summary->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1)
    { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->order_summary->destroy($id);
        } else {
            return $this->order_summary->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id)
    {
        return $this->order_summary->withTrashed()->find($id)->restore();
    }

    public function orderFroms($column = null, $value = null)
    {
        if($value == 'marketplace'){
            $data['marketplace'] = 'Marketplace';
            return $data;
        }

        $getFromDatas = $this->order_summary
                    ->where($column, $value)
                    ->pluck('invoice_id');
        foreach($getFromDatas as $key => $getFromData){
            $customerInvoice = CustomerInvoice::where('invoice_id', $getFromData)->first();
            if(isset($customerInvoice)){
                if($customerInvoice->invoice_type == 'auction'){
                    $data[$customerInvoice->auction->id] = $customerInvoice->auction->title;
                }
            }
        }

        return $data;
    }

    public function orderCustomers($column = null, $value = null)
    {
        return $this->order_summary
                    ->where($column, $value)
                    ->get()->unique('customer_id')->pluck('customer.search_full_name');

    }
}
