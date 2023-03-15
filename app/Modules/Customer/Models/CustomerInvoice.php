<?php

namespace App\Modules\Customer\Models;

use App\Modules\Auction\Models\Auction;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Xero\Models\XeroInvoice;
use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use App\Modules\Customer\Models\CustomerMarketplaceItem;

class CustomerInvoice extends Model
{
    protected $table = 'customer_invoices';

    protected $fillable = ['customer_id', 'auction_id', 'invoice_id', 'invoice_type', 'type', 'invoice_date', 'xero_invoice_data', 'invoice_url', 'order_summary_id', 'active'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function items()
    {
        if ($this->invoice_type == 'marketplace' && $this->type == 'invoice') {
            return $this->hasMany(CustomerMarketplaceItem::class, 'customer_invoice_id');
        } else {
            return $this->hasMany(CustomerInvoiceItem::class, 'customer_invoice_id');
        }
    }

    public function invoice()
    {
        if ($this->invoice_id == null || $this->xero_invoice_data == null) {
            return null;
        }
        return json_decode($this->xero_invoice_data);
    }

    public function xeroInvoiceBuyers()
    {
        return $this->hasMany(XeroInvoice::class, 'buyer_id');
    }

    public function xeroInvoiceSellers()
    {
        return $this->hasMany(XeroInvoice::class, 'seller_id');
    }

    public function getStatusAttribute()
    {
        if (is_null($this->xero_invoice_data)) {
            return null;
        }
        $status = json_decode($this->xero_invoice_data)->Status;
        switch ($status) {
            case 'PAID':
                return 'Paid';
                break;
            case 'AUTHORISED':
                return 'Awaiting Payment';
                break;
            case 'SUBMITTED':
                return 'Awaiting Approval';
                break;
            default:
                return 'Draft';
        }
    }

    public static function url($customerInvoiceId)
    {
        $customerInvoice = CustomerInvoice::where('id',$customerInvoiceId)->first();

        if($customerInvoice && $customerInvoice->invoice_id != null){
            if($customerInvoice->type == 'bill'){
                $customerInvoice->invoice_url = null;
                $customerInvoice->save();
            }
            if ($customerInvoice->invoice_url == null) {
                $accountingAutomate = resolve('App\Modules\Xero\Accounting\Automate');

                if ($customerInvoice->type == 'bill') {
                    $invoice_url = $accountingAutomate->getInvoiceAsPdf($customerInvoice->invoice_id);
                    $customerInvoice->invoice_url = $invoice_url;
                    $customerInvoice->save();
                    if ($invoice_url == null) {
                        return $invoice_url;
                    }
                    return $invoice_url . '?' . time();
                }

                $invoice_url = $accountingAutomate->getInvoiceUrl($customerInvoice->invoice_id);
                $customerInvoice->invoice_url = $invoice_url;
                $customerInvoice->save();
                if($invoice_url == null){
                    return $invoice_url;
                }
                return $invoice_url . '?' . time();
            }else{
                return $customerInvoice->invoice_url . '?' . time();
            }

        }else{
             return null;
        }
    }

    public static function getInvoiceReference($customerInvoiceId)
    {
        $customerInvoice = CustomerInvoice::where('id',$customerInvoiceId)->first();

        if($customerInvoice == null){
            return null;
        }
        if (is_null($customerInvoice->xero_invoice_data)) {
            return null;
        }

        return json_decode($customerInvoice->xero_invoice_data)->Reference;
    }

    public function getInvoiceNumberAttribute()
    {
        if (is_null($this->xero_invoice_data)) {
            return null;
        }
        return json_decode($this->xero_invoice_data)->InvoiceNumber;
    }

    public function getInvoiceAmountAttribute()
    {
        if (is_null($this->xero_invoice_data)) {
            return 0;
        }

        $status = json_decode($this->xero_invoice_data)->Status;

        if($status == 'PAID'){
            $total = json_decode($this->xero_invoice_data)->AmountPaid;
        }else{
            $total = json_decode($this->xero_invoice_data)->AmountDue;
        }

        return $total;
    }
}
