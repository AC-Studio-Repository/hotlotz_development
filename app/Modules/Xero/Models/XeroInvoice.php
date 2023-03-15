<?php

namespace App\Modules\Xero\Models;

use App\Traits\UUID;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\Customer;

class XeroInvoice extends Model
{
    use UUID;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['type', 'buyer_id', 'seller_id', 'item_id', 'auction_id', 'price', 'sold_price_inclusive_gst', 'sold_price_exclusive_gst'];

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function seller()
    {
        return $this->belongsTo(Customer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Getting buyer premiun value by price function
     *
     * @param integer $buyerPremiun
     * @return float
     */
    public function getBuyerPremiun($buyerPremiun = 25)
    {
        return ($buyerPremiun / 100) * $this->price;
    }

}
