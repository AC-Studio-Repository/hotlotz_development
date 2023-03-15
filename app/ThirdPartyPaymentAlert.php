<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThirdPartyPaymentAlert extends Model
{
    use SoftDeletes;

    public $table = 'third_party_payment_alerts';

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'invoice_id', 'invoice_number', 'amount', 'payment_method', 'payment_data'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentData()
    {
        if ($this->payment_data == null) {
            return null;
        }
        return json_decode($this->payment_data);
    }

}
