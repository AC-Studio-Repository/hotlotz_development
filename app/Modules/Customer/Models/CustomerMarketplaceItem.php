<?php

namespace App\Modules\Customer\Models;

use App\Modules\Item\Models\Item;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\CustomerInvoice;

class CustomerMarketplaceItem extends Model
{
    protected $table = 'customer_marketplace_items';

    protected $fillable = ['customer_invoice_id', 'item_id', 'price'];

    public function invoice()
    {
        return $this->belongsTo(CustomerInvoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
