<?php

namespace App\Modules\OrderSummary\Models;

use Illuminate\Support\Str;
use App\Modules\Item\Models\Item;
use Illuminate\Support\HtmlString;
use Konekt\Address\Models\Address;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\OrderSummary\Events\OrderWasCreated;
use App\Modules\OrderSummary\Events\OrderWasUpdated;

class OrderSummary extends Model
{
    /**
     * Pending orders are new orders that have not been processed yet.
     */
    const PENDING = 'pending';

    /**
     * Orders that has been paided.
     */
    const PAID = 'paid';

    /**
     * Orders fulfilled completely.
     */
    const COMPLETED = 'completed';

    /**
     * Order that has been cancelled.
     */
    const CANCELLED = 'cancelled';

    use SoftDeletes;

    protected $keyType = 'string';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $incrementing = false;

    public $table = 'order_summaries';

    protected $dispatchesEvents = [
        'created' => OrderWasCreated::class,
        'updated' => OrderWasUpdated::class,
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class)->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
            $model->reference_no = static::generateRefNumber();
        });
    }

    public static function generateRefNumber()
    {
        $last = OrderSummary::count();
        $next = 1 + $last;

        return sprintf(
            '%s%s',
            'PO-',
            str_pad($next, 8, 0, STR_PAD_LEFT)
        );
    }

    public static function getOrderFrom($invoice_id)
    {
        $customerInvoice = CustomerInvoice::where('invoice_id', $invoice_id)->first();
        if(isset($customerInvoice)){
            if($customerInvoice->invoice_type !== 'auction'){
                return $customerInvoice->invoice_type;
            }else{
                return new HtmlString('<a target="_blank" href="'.route('auction.auctions.show', $customerInvoice->auction).'"> ' . $customerInvoice->auction->title .'</a>');
            }
        }else{
            return null;
        }

    }

    public static function getOrderTotalWithUrl($order)
    {
        $customerInvoice = CustomerInvoice::where('invoice_id', $order->invoice_id)->first();
        if(isset($customerInvoice)){
            return new HtmlString('<a target="_blank" href="'.$customerInvoice->url($customerInvoice->id).'"> ' . $order->total .'</a>');
        }else{
            return $order->total;
        }

    }
}
