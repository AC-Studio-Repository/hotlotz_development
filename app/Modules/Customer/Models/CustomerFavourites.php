<?php

namespace App\Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Models\Item;

class CustomerFavourites extends Model
{
    protected $table = 'customer_favourites';

    protected $fillable = ['customer_id', 'item_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}