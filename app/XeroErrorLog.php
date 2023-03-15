<?php

namespace App;

use App\Modules\Item\Models\Item;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\Customer;

class XeroErrorLog extends Model
{
    public $table = 'xero_error_logs';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id', 'buyer_id', 'item_id', 'amount', 'invoice_id', 'type', 'unique_key'
    ];

    public function buyer()
    {
        return $this->belongsTo(Customer::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(Customer::class, 'seller_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
