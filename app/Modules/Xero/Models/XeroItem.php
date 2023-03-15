<?php

namespace App\Modules\Xero\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\CustomerInvoiceItem;

class XeroItem extends Model
{
    public $timestamps = false;

    public $table = 'xero_items';

    protected $fillable = ['item_code', 'item_name', 'purchases_description', 'purchases_account', 'sales_description', 'sales_account', 'xero_product_id', 'sale_tax_rate'];

    public function invoiceItems()
    {
        return $this->hasMany(CustomerInvoiceItem::class, 'xero_item_id');
    }
}
