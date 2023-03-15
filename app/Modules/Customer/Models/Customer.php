<?php

namespace App\Modules\Customer\Models;

use Auth;
use Hash;
use App\User;
use App\XeroErrorLog;
use App\Models\Country;
use App\Models\TimeZone;
use App\ThirdPartyPaymentAlert;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Events\CustomerCreatedEvent;
use App\Events\CustomerUpdatedEvent;
use Illuminate\Notifications\Notifiable;
use App\Modules\WhatWeSell\Models\WhatWeSell;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Modules\Customer\Models\CustomerInterests;
use App\Notifications\CustomerVerifyEmailNotification;
use App\Notifications\CustomerResetPasswordNotification;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Customer extends \Konekt\Customer\Models\Customer implements Authenticatable, CanResetPassword
{
    use AuthenticableTrait, CanResetPasswordTrait;
    use SoftDeletes, Notifiable;

    public $table = 'customers';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'sr_customer_data' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => CustomerCreatedEvent::class,
        'updated' => CustomerUpdatedEvent::class,
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPasswordNotification($token));
    }

    public function timezone()
    {
        return $this->belongsTo(TimeZone::class, 'time_zone');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_of_residence');
    }

    public function invoices()
    {
        return $this->hasMany(CustomerInvoice::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'customer_id');
    }

    public function purchaseditems()
    {
        return $this->hasMany(Item::class, 'buyer_id');
    }

    public function mainclientcontact()
    {
        return $this->belongsTo(User::class, 'main_client_contact');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getNameById($id)
    {
        $customer = Customer::find($id);
        return $customer->fullname;
    }

    protected function getCustomerDocumentData($id, $type = 'document')
    {
        $customer_documents = CustomerDocument::where('customer_id', $id)
                ->where(function ($query) use ($type) {
                    if($type == 'document'){
                        $query->whereNull('type');
                        $query->orWhere('type', $type);
                    }else{
                        $query->where('type', $type);
                    }
                })
                ->get();
        // dd($customer_documents->toArray());

        $customer_initialpreview = [];
        $customer_initialpreviewconfig = [];
        $hide_customer_ids = '';
        foreach ($customer_documents as $key => $cust_document) {
            $ext = pathinfo(asset($cust_document->file_path), PATHINFO_EXTENSION);
            if (in_array($ext, ["jpg", "jpeg", "png"])) {
                $ext = 'image';
            }

            $customer_initialpreview[] = $cust_document->full_path;
            $customer_initialpreviewconfig[] = [
                'caption'=>$cust_document->file_name,
                'type' => $ext,
                // 'size'=>'57071', 'width'=>"263px", 'height'=>"217px",
                'url'=>'/manage/customers/'.$cust_document->id.'/document_delete',
                'key'=>$cust_document->id,
                'extra' => ['_token'=>csrf_token()]
            ];
            $hide_customer_ids .= $cust_document->id . ',';
        }

        return array(
            'customer_initialpreview'=>$customer_initialpreview,
            'customer_initialpreviewconfig'=>$customer_initialpreviewconfig,
            'hide_customer_ids'=>$hide_customer_ids,
        );
    }

    public static function getCustomerRefNo($customer_id = null)
    {
        if ($customer_id == null) {
            $checkLatestCustomer = Customer::withTrashed()->latest()->first();

            if ($checkLatestCustomer) {
                $getIntegerInString = filter_var($checkLatestCustomer->ref_no, FILTER_SANITIZE_NUMBER_INT);
                $number = (int) $getIntegerInString;
                $str = str_replace($getIntegerInString, "", $checkLatestCustomer->ref_no);
                return Customer::generateRefNo($str, $number);
            } else {
                return Customer::generateRefNo();
            }
        }

        if ($customer_id !== null) {
            $latestCustomerId = $customer_id - 1;
            $checkLatestCustomer = Customer::withTrashed()->find($latestCustomerId);
            if ($checkLatestCustomer) {
                $getIntegerInString = filter_var($checkLatestCustomer->ref_no, FILTER_SANITIZE_NUMBER_INT);
                $number = (int) $getIntegerInString;
                $str = str_replace($getIntegerInString, "", $checkLatestCustomer->ref_no);

                return Customer::generateRefNo($str, $number);
            } else {
                return Customer::generateRefNo();
            }
        }
    }

    public static function generateRefNo($str = 'A', $number = 500)
    {
        ++$number;
        if ($number % 1000 == 0) {
            ++$str;
            $number = 500;
            return $str . str_pad($number, 3, "0", STR_PAD_LEFT);
        } else {
            return $str . str_pad($number, 3, "0", STR_PAD_LEFT);
        }
    }

    /**
     * Get buyer_gst_stauts base on
     * country of residence sigapore &
     * buyer gst registered yes
     * @return boolean
     */
    public function getBuyerGstStatusAttribute()
    {
        if ($this->buyer_gst_registered == 1) {
            return 1;
        }
        return 0;
    }

    public function getCreditCardsAttribute()
    {
        try {
            \Stripe\Stripe::setApiKey(setting('services.stripe.secret'));

            if ($this->stripe_customer_id != null) {
                $payment_methods = \Stripe\PaymentMethod::all([
                    'customer' => $this->stripe_customer_id,
                    'type' => 'card'
                ]);
                if ($payment_methods->data == []) {
                    return null;
                }
                $save_payments = [];
                $fingerprints = [];
                foreach ($payment_methods->data as $key => $payment_method) {
                    $fingerprint = $payment_method['card']['fingerprint'];
                    if (in_array($fingerprint, $fingerprints, true)) {
                    } else {
                        if ($payment_method->metadata['is_save'] == true) {
                            $save_payments[$key] = $payment_method;
                        }
                        $fingerprints[] = $fingerprint;
                    }
                }
                return $save_payments;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getRegisterCreditCardsAttribute()
    {
        try {
            \Stripe\Stripe::setApiKey(setting('services.stripe.secret'));

            if ($this->stripe_customer_id != null) {
                $payment_methods = \Stripe\PaymentMethod::all([
                    'customer' => $this->stripe_customer_id,
                    'type' => 'card'
                ]);
                if ($payment_methods->data == []) {
                    return null;
                }
                return $payment_methods->data;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getAwaitingPaymentCountAttribute()
    {
        return count($this->invoices()->where('type', '=', 'invoice')->where('active', 1)->get()->reject(function ($invoice) {
            return $invoice->status != 'Awaiting Payment';
        })->groupBy('invoice_id'));
    }

    public function getCustomerPreferenceStatusAttribute()
    {
        $incomplete = '';
        $flag_intrest = false;
        $flag_campaign = false;
        $sellItems = WhatWeSell::get()->pluck('id');
        $intrests = CustomerInterests::where('customer_id', $this->id)->whereIn('what_we_sell_id', $sellItems)->get();
        $item_count = $sellItems->count();
        $intrest_count = $intrests->count();

        if ($intrest_count > 0) {
            $flag_intrest = true;
        } else {
            $flag_intrest = false;
        }

        if (Auth::guard('customer')->user()->marketing_auction == 0 && Auth::guard('customer')->user()->marketing_marketplace == 0 && Auth::guard('customer')->user()->marketing_chk_events == 0 && Auth::guard('customer')->user()->marketing_chk_congsignment_valuation == 0 && Auth::guard('customer')->user()->marketing_hotlotz_quarterly == 0) {
            $flag_campaign = false;
        } else {
            $flag_campaign = true;
        }
        if ($flag_intrest == true && $flag_campaign == true) {
            $incomplete = 'complete';
        }
        return $incomplete;
    }

    public function getSelllerAgreementCountAttribute()
    {
        $pending_approval_items = Item::where('items.customer_id', $this->id)
                ->where('permission_to_sell', '!=', 'Y')
                ->where('is_valuation_approved', 'Y')
                ->where('is_fee_structure_approved', 'Y')
                ->select('items.id')
                ->count();

        return $pending_approval_items;
    }

    public function getConsignmentCountAttribute()
    {
        $myconsignmentItems = Item::where('items.customer_id', '=', $this->id)
            ->select('items.id')
            ->count();

        return $myconsignmentItems;
    }

    public function getSaleContractCountAttribute()
    {
        $contracts = Item::where('items.customer_id', $this->id)
                ->where('permission_to_sell', 'Y')
                ->where('is_valuation_approved', 'Y')
                ->where('is_fee_structure_approved', 'Y')
                ->select('items.id')
                ->count();

        return $contracts;
    }

    public static function getSelect2CustomerDataOld($auction_id = null)
    {
        if($auction_id){
            $auctionCustomers = DB::table('auction_items')->where('auction_items.auction_id', $auction_id)
                            ->whereNotNull('auction_items.lot_id')
                            ->whereNotNull('auction_items.status')
                            ->join('items', 'items.id', 'auction_items.item_id')
                            ->where('items.status', '!=', Item::_DECLINED_)
                            ->whereNull('items.deleted_at')
                            ->select('items.customer_id as customer_id')
                            ->get();

            $customers = Customer::whereIn('id',$auctionCustomers->pluck('customer_id'))->select('id', 'fullname', 'ref_no', 'firstname', 'lastname')->get();
        }else{
            $customers = Customer::select('id', 'fullname', 'ref_no', 'firstname', 'lastname')->get();
        }
        $sellers = [];
        foreach ($customers as $key => $customer) {
            $fullname = ($customer->fullname != null && $customer->fullname != '')?$customer->fullname:($customer->firstname.' '.$customer->lastname);
            if($customer->type->value() == 'organization' && $customer->company_name != null && $customer->company_name != ''){
                $fullname = $customer->company_name;
            }

            $sellers[] = [
                'id' => $customer->id,
                'text' => $customer->ref_no.'_'.$fullname,
            ];
        }
        $results = $sellers;
        array_unshift($results, array("id"=>"","text"=>""));
        return $results;
    }

    public static function getSelect2CustomerData($filter = [], $auction_id = null)
    {
        $counts = 0;
        if($auction_id){
            $auctionCustomers = DB::table('auction_items')->where('auction_items.auction_id', $auction_id)
                            ->whereNotNull('auction_items.lot_id')
                            ->whereNotNull('auction_items.status')
                            ->join('items', 'items.id', 'auction_items.item_id')
                            ->where('items.status', '!=', Item::_DECLINED_)
                            ->whereNull('items.deleted_at')
                            ->select('items.customer_id as customer_id')
                            ->get();

            $customers = Customer::whereIn('id',$auctionCustomers->pluck('customer_id'))->select('id', 'fullname', 'ref_no','firstname','lastname')->get();
            $counts = $customers->count();

        }else if($filter){
            $customers = Customer::where('fullname','like','%'.$filter['search'].'%')
                    ->orWhere('ref_no','like','%'.$filter['search'].'%')
                    ->select('id', 'fullname', 'ref_no','firstname','lastname');
            $counts = $customers->count();
            $customers = $customers->paginate(10);

        }else{
            $customers = Customer::select('id', 'fullname', 'ref_no','firstname','lastname')->get();
            $counts = $customers->count();
        }
        $sellers = [];
        foreach ($customers as $key => $customer) {
            $fullname = ($customer->fullname != null && $customer->fullname != '')?$customer->fullname : $customer->select2_fullname;
            $sellers[] = [
                'id' => $customer->id,
                'text' => $customer->ref_no.'_'.$fullname,
            ];
        }
        $results = $sellers;
        array_unshift($results, array("id"=>"","text"=>""));
        return ['result'=>$results, 'counts'=>$counts];
    }

    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomerVerifyEmailNotification());
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }

    public static function getCustomerInterests($customer_id)
    {
        $customer_interests = CustomerInterests::where('customer_id', $customer_id)->get();

        $category_interests = [];
        if (!$customer_interests->isEmpty()) {
            foreach ($customer_interests as $value) {
                array_push($category_interests, $value->what_we_sell_id);
            }
        }
        return $category_interests;
    }

    public function markAsAgreed()
    {
        return $this->update([
            'has_agreement' => 1
        ]);
    }

    public function getSearchFullNameAttribute()
    {
        // dd($this->type->value());
        if ($this->type->value() == 'organization') {
            return $this->company_name . ' ('. $this->ref_no . ')';
        }
        return $this->fullname . ' ('. $this->ref_no . ')';
    }

    public function getXeroFullNameAttribute()
    {
        if ($this->type->value() == 'organization') {
            return $this->company_name . ' ('. $this->ref_no . ')';
        }
        return $this->fullname . ' ('. $this->ref_no . ')';
    }

    /**
     * Get Exclude from marketing material
     * @return boolean
     */
    public function getExcludeMarketingMaterialAttribute()
    {
        if ($this->marketing_auction == 0 && $this->marketing_marketplace == 0 && $this->marketing_chk_events == 0 && $this->marketing_chk_congsignment_valuation == 0 && $this->marketing_hotlotz_quarterly == 0) {
            return 1;
        }
        return 0;
    }

    public function getcustomBankAccountAttribute()
    {
        $bank_account = str_replace(' ', '', $this->bank_account_number);//343546578902
        $bank_account = str_replace('-', '', $bank_account);//343546578902
        $pattern = "/[0-9]/";
        $first = substr($bank_account, 0, -3);
        $last = substr($bank_account, -3, 3);
        // $bank_account = preg_replace($pattern, '*', $first).$last;
        $bank_account = $first.$last;
        return $bank_account;
    }

    public function thirdPartyPaymentAlerts()
    {
        return $this->hasMany(ThirdPartyPaymentAlert::class, 'customer_id');
    }

    public function xeroErrorLogBuyer()
    {
        return $this->hasMany(XeroErrorLog::class, 'buyer_id');
    }

    public function xeroErrorLogSeller()
    {
        return $this->hasMany(XeroErrorLog::class, 'seller_id');
    }

    public function getKycStatusAttribute()
    {
        $status = null;
        $flag_info = false;
        $flag_citizenship = false;
        $flag_identity = false;

        if($this->legal_name != null && $this->date_of_birth != null && $this->occupation != null && $this->citizenship_type != null && $this->id_type != null && $this->uploaded_date != null){
            $flag_info = true;
        }

        if($this->citizenship_type != null){
            $flag_citizenship = false;
            if($this->citizenship_type == 'single' && $this->citizenship_one != null){
                $flag_citizenship = true;
            }
            if($this->citizenship_type == 'dual' && $this->citizenship_one != null && $this->citizenship_two != null){
                $flag_citizenship = true;
            }
        }

        if($this->id_type != null && $this->id_type == 'nric'){
            $nric_documents = CustomerDocument::where('customer_id', $this->id)->where('type', 'nric')->get();
            $nric_documents_count = $nric_documents->count();

            if ($this->nric != null && $nric_documents_count > 0) {
                $flag_identity = true;
            }
        }
        if($this->id_type != null && $this->id_type == 'fin'){
            $fin_documents = CustomerDocument::where('customer_id', $this->id)->where('type', 'fin')->get();
            $fin_documents_count = $fin_documents->count();

            if ($this->fin != null && $fin_documents_count > 0) {
                $flag_identity = true;
            }
        }
        if($this->id_type != null && $this->id_type == 'passport'){
            $passport_documents = CustomerDocument::where('customer_id', $this->id)->where('type', 'passport')->get();
            $passport_documents_count = $passport_documents->count();

            if ($this->country_of_issue != null && $this->passport != null && $this->passport_expiry_date != null && $passport_documents_count > 0) {
                $flag_identity = true;
            }
        }

        if ($this->legal_name != null || $this->date_of_birth != null || $this->occupation != null || $this->citizenship_type != null || $this->id_type != null || $this->uploaded_date != null || $this->citizenship_one != null || $this->id_type != null || $flag_info == true || $flag_citizenship == true || $flag_identity == true) {
            $status = 'partial';
        }

        if ($flag_info == true && $flag_citizenship == true && $flag_identity == true) {
            $status = 'complete';
        }
        
        return $status;
    }

    public function getSelect2FullNameAttribute()
    {
        $fullname = $this->fullname;
        if($this->fullname == null || $this->fullname == ''){
            $fullname = $this->firstname.' '.$this->lastname;
        }
        return $fullname;
    }

    public function getClientFullNameAttribute()
    {
        $fullname = $this->fullname;
        if($this->fullname == null || $this->fullname == ''){
            $fullname = $this->firstname.' '.$this->lastname;
        }
        if($this->type->value() == 'organization' && $this->company_name != null){
            $fullname = $this->company_name;
        }
        return $fullname;
    }
}
