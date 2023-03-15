<?php

namespace App\Modules\Customer\Models;

use App\Modules\Item\Models\Item;
use App\Modules\Xero\Models\XeroItem;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\CustomerInvoice;

class CustomerInvoiceItem extends Model
{
    protected $table = 'customer_invoice_items';

    protected $fillable = ['customer_invoice_id', 'item_id', 'xero_item_id', 'price', 'cancel_sale'];

    public function invoice()
    {
        return $this->belongsTo(CustomerInvoice::class);
    }

    public function item()
    {
        if ($this->xero_item_id == null) {
            return $this->belongsTo(Item::class);
        } else {
            return $this->belongsTo(XeroItem::class);
        }
    }
}
