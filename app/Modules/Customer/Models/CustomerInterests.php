<?php

namespace App\Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\Customer;
use App\Modules\WhatWeSell\Models\WhatWeSell;

class CustomerInterests extends Model
{
    protected $table = 'customer_interests';

    protected $fillable = ['customer_id', 'what_we_sell_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function item()
    {
        return $this->belongsTo(WhatWeSell::class);
    }
}
