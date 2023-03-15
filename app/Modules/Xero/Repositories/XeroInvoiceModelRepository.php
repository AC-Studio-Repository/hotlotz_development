<?php

namespace App\Modules\Xero\Repositories;

use App\Modules\Xero\Models\XeroInvoice;

class XeroInvoiceModelRepository
{
    public function __construct(XeroInvoice $xeroinvoice)
    {
        $this->xeroinvoice = $xeroinvoice;
    }

    public function get($id = 'all', $autions = 'all', $customers = 'all', $type = 'all', $items = 'all', $for = 'auction', $status = 0)
    {
        $query = $this->xeroinvoice
                    ->when($status == 0, function ($query) {
                        return $query->whereIn('status', [ 0, 2, 3 ]);
                    })
                    ->when($status != 0, function ($query) use ($status) {
                        return $query->where('status', $status);
                    })
                    ->when($id != 'all', function ($query) use ($id) {
                        return $query->where('id', $id);
                    })
                    ->when($autions != 'all', function ($query) use ($autions) {
                        return $query->whereIn('auction_id', $autions);
                    })
                    ->when($items != 'all', function ($query) use ($items) {
                        return $query->whereIn('item_id', $items);
                    })
                    ->when($customers != 'all', function ($query) use ($customers, $type) {
                        if ($type == 'bill') {
                            return $query->whereIn('seller_id', $customers);
                        }
                        if ($type == 'invoice') {
                            return $query->whereIn('buyer_id', $customers);
                        }
                    })
                    ->when($for, function ($query) use ($for) {
                        return $query->where('type', $for);
                    })
                    ->get();

        return $query->groupBy('buyer_id')->take(5);

    }

    public function updateStatus($id)
    {
        return $this->xeroinvoice
                    ->find($id)->update(['status' => 1]);
    }

    public function destroy($id)
    {
        return $this->xeroinvoice->delete($id);
    }
}
