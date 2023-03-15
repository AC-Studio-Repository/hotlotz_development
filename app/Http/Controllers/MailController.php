<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\Mail;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\ItemHistory;
use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Models\CustomerInvoice;

class MailController extends Controller
{
    public function __construct()
    {

    }

    public function test()
    {
        return new \App\Mail\TestMail();
    }

    public function accountActivate($id){
        $customer = Customer::where('ref_no', $id)->first();
        return new \App\Mail\User\Activate($customer);
    }

    public function settlement(){
        return new \App\Mail\User\Settlement();
    }

    public function forgetPassword(){
        return new \App\Mail\User\ForgetPassword();
    }

    public function submission($id, $action=null){

        $customer = Customer::where('ref_no', $id)->first();

        $items = Item::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->take(5)->get('name')->toArray();

        if($action == "send"){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\SubmissionReceived($customer, $items));
            echo "Success";
        }

        return new \App\Mail\Item\SubmissionReceived($customer, $items);
    }

    public function itemConfirmationToCustomer($id){

        $customer = Customer::where('ref_no', $id)->first();

        return new \App\Mail\Item\Confirmation($customer);
    }

    public function itemReceiptForDropOff($id, $action=null){

        $customer = Customer::where('ref_no', $id)->first();

        if($action == "send"){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\DroppedOffItemReceived($customer));
            echo "Success";
        }

        return new \App\Mail\Item\DroppedOffItemReceived($customer);
    }

    public function sellerAgreement(){
        return new \App\Mail\Item\SellerAgreement();
    }

    public function sellerAgreementConfirmation(){
        return new \App\Mail\Item\SellerAgreementConfirmation();
    }

    public function consignmentUpdate($id, $from = null, $to = null){

        $customer = Customer::where('ref_no', $id)->first();

        $data = $this->getConsignmentUpdate($customer, $from, $to);

        if(Arr::has($data, $customer->id))
            return new \App\Mail\Item\ConsignmentUpdate($data[$customer->id]);
        else
            return new \App\Mail\Item\ConsignmentUpdate([]);
    }

    public function sendConsignmentUpdate($id, $from = null, $to = null){

        $customer = Customer::where('ref_no', $id)->first();

        $data = $this->getConsignmentUpdate($customer, $from, $to);

        if(Arr::has($data, $customer->id)){
            Mail::to($customer->email)
                ->send(new \App\Mail\Item\ConsignmentUpdate($data[$customer->id]));
            echo "Success";
            return new \App\Mail\Item\ConsignmentUpdate($data[$customer->id]);
        }else{
            echo "No Data for this Customer";
            return new \App\Mail\Item\ConsignmentUpdate([]);
        }
    }

    private function getConsignmentUpdate($customer, $from, $to){

        if($from == null) $from = date('Y-m-d');
        if($to == null) $to = date('Y-m-d');

        $item_histories = ItemHistory::where('customer_id', $customer->id)
            ->where('entered_date', '<', $to)
            ->where('entered_date', '>', $from)
            ->get();
        \Log::channel('emailLog')->info('Count of item_histories : '.count($item_histories));

        $data = [];
        foreach ($item_histories as $key => $item_history) {
            $item = Item::find($item_history->item_id);
            $auction = Auction::find($item_history->auction_id);

            $ar_statuses = [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_UNSOLD_];
            if( $item_history->type == 'auction' && in_array($item_history->status, $ar_statuses)){

                $data[$item_history->customer_id]['auction_results'][] = [
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'auction_id' => $item_history->auction_id,
                    'auction_name' => isset($auction)?$auction->title:null,
                    'buyer_id' => $item_history->buyer_id,
                    'price' => $item_history->price,
                    'sold_price' => $item->sold_price,
                    'type' => $item_history->type,
                    'status' => $item_history->status,
                ];
            }

            if( $item_history->type == 'marketplace' && in_array($item_history->status, $ar_statuses)){

                $data[$item_history->customer_id]['mp_results'][] = [
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'auction_id' => null,
                    'auction_name' => null,
                    'buyer_id' => $item_history->buyer_id,
                    'price' => $item_history->price,
                    'sold_price' => $item->sold_price,
                    'type' => $item_history->type,
                    'status' => $item_history->status,
                ];
            }

            $lifecycle_statues = [Item::_AUCTION_, Item::_MARKETPLACE_, Item::_CLEARANCE_, Item::_STORAGE_];
            if($item_history->type == 'lifecycle' && in_array($item_history->status, $lifecycle_statues)){

                $data[$item_history->customer_id]['notifications'][] = [
                    'item_id' => $item_history->item_id,
                    'item_name' => $item->name,
                    'auction_id' => $item_history->auction_id,
                    'auction_name' => isset($auction)?$auction->title:null,
                    'price' => $item_history->price,
                    'type' => $item_history->type,
                    'status' => $item_history->status,
                ];
            }
        }
        return $data;
    }

    public function storage($id){
	$customer = Customer::where('ref_no', $id)->first();
        $items = Item::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->take(5)->get('name')->toArray();
        return new \App\Mail\Item\Storage($items);
    }

    public function storageFee($id){
        $customer = Customer::where('ref_no', $id)->first();
        $items = Item::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->take(5)->get('name')->toArray();
        $type = 'first';
        return new \App\Mail\Item\StorageFee($items, $type);
    }

    public function storageFeeSecond($id){
        $customer = Customer::where('ref_no', $id)->first();
        $items = Item::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->take(5)->get('name')->toArray();
        $type = 'second';
        return new \App\Mail\Item\StorageFee($items, $type);
    }

    public function declined($id){
        $customer = Customer::where('ref_no', $id)->first();

        // $items = Item::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->take(5)->get('name')->toArray();
        
        $item = Item::where('customer_id', $customer->id)->first();
        
        return new \App\Mail\Item\Declined($item);
    }

    public function welcome($id){
        return new \App\Mail\User\Welcome(Customer::where('ref_no', $id)->first());
    }

    public function sendWelcome($id){
        $customer = Customer::where('ref_no', $id)->first();

        $customer->update(
            [
                'email_verified_at' => null,
                'has_agreement' => 0,
            ]
        );

        Mail::to($customer->email)
            ->send(new \App\Mail\User\Welcome($customer));
        echo "Success";
        return new \App\Mail\User\Welcome($customer);
    }

    public function withdraw($id){
        $item = Item::find($id);
        return new \App\Mail\Item\Withdraw($item);
    }

    public function cancelSale($id){
	$item = Item::find($id);
        return new \App\Mail\Item\CancelSale($item);
    }

    public function paymentReceipt(){
        $itemNames = [
            'Item One',
            'Item Two'
        ];

        return new \App\Mail\User\PaymentReceipt($itemNames);
    }


    public function saleroomCustomerRegister($id, $auction_id){
        // route example => http://hotlotz.local/mail/user/saleroom/E694/hotzlo10156
        $customer = Customer::where('ref_no', $id)->first();
        $auction = Auction::where('sr_reference', $auction_id)->first();
        $items = \DB::select("SELECT * FROM items WHERE id IN (SELECT item_id  FROM `xero_invoices` WHERE `buyer_id` = $customer->id AND `auction_id` = '$auction->id')");

        return new \App\Mail\User\SaleroomCustomerRegister($customer, $auction->title, $items);
    }

    public function sendSaleroomCustomerRegister($id, $auction_id){
        $customer = Customer::where('ref_no', $id)->first();
        $auction = Auction::where('sr_reference', $auction_id)->first();
        $items = \DB::select("SELECT name FROM items WHERE id IN (SELECT item_id  FROM `xero_invoices` WHERE `buyer_id` = $customer->id AND `auction_id` = '$auction->id')");

        Mail::to($customer->email)
            ->send(new \App\Mail\User\SaleroomCustomerRegister($customer, $auction->title, $items));
        echo "Success";

        return new \App\Mail\User\SaleroomCustomerRegister($customer, $auction->title, $items);
    }

    public function auctionInvoice($customer_ref, $auction_ref){
        $customer = Customer::where('ref_no', $customer_ref)->first();
        $auction = Auction::where('sr_reference', $auction_ref)->first();
        $customerInvoice = CustomerInvoice::where('customer_id', $customer->id)->where('auction_id', $auction->id)->first();
        return new \App\Mail\User\AuctionInvoice($customerInvoice);
    }

    public function invite($id){
        return new \App\Mail\User\Invite(Customer::where('ref_no', $id)->first());
    }

    public function sendInvite($id){
        $customer = Customer::where('ref_no', $id)->first();

        $customer->update(
            [
                'email_verified_at' => null,
                'has_agreement' => 0,
            ]
        );

        Mail::to($customer->email)
            ->send(new \App\Mail\User\Invite($customer));
        echo "Success";
        return new \App\Mail\User\Invite($customer);
    }

    public function invoice($id){

        $customer = Customer::where('ref_no', $id)->first();
        return new \App\Mail\User\ManualAuctionInvoice($customer);
    }

    public function sendInvoice($id){

        $customer = Customer::where('ref_no', $id)->first();
        \Log::channel('emailLog')->info('Sending Manual Invoice Email to ' . $customer->fullname . "(" . $customer->ref_no . ")");
        Mail::to($customer->email)
            ->send(new \App\Mail\User\ManualAuctionInvoice($customer));
        echo "Success";
        return new \App\Mail\User\ManualAuctionInvoice($customer);
    }

    public function privatesale(){
        return new  \App\Mail\User\PrivateInvoice();
    }

    public function weeklySellWithUs(){
        $items = Item::where('status', Item::_SWU_)->get()->random(10);
        $to = Carbon::parse(date('Y-m-d'))->next('Monday');
        $from = Carbon::parse(date('Y-m-d'))->previous('Monday');

        $to = $to->hour(9);
        $from = $from->hour(9)->minute(1);

        return new \App\Mail\Admin\WeeklySellWithUs($items, $from, $to);
    }

    public function kycBuyer($id){
        $customer = Customer::where('ref_no', $id)->first();
        return new \App\Mail\User\KycBuyerEmail($customer);
    }

    public function kycCompanySeller($id){
        $customer = Customer::where('ref_no', $id)->first();
        return new \App\Mail\User\KycBuyerEmail($customer);
    }

    public function kycIndividualSeller($id){
        $customer = Customer::where('ref_no', $id)->first();
        return new \App\Mail\User\KycBuyerEmail($customer);
    }

    public function manualAuction($id){
        $customer = Customer::where('ref_no', $id)->first();
        return new \App\Mail\User\ManualAuctionInvoice($customer);
    }

    public function saleRoomCustomerRegisterWithItem($id, $auction_id){
        // route example => http://hotlotz.local/mail/user/saleroom/E694/hotzlo10156
        $customer = Customer::where('ref_no', $id)->first();
        $auction = Auction::where('sr_reference', $auction_id)->first();
        $items = \DB::select("SELECT * FROM items WHERE id IN (SELECT item_id  FROM `xero_invoices` WHERE `buyer_id` = $customer->id AND `auction_id` = '$auction->id')");

        return new \App\Mail\User\SaleroomCustomerRegisterWithItems($customer, $auction->title, $items);
    }

}
