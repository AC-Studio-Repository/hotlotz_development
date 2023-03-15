<?php

namespace App\Repositories;

use DB;
use Auth;
use  App\Models\Country;

use App\Helpers\SampleHelper;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Customer\Models\CustomerDocument;
use App\Modules\Customer\Models\CustomerInterests;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use App\Modules\Customer\Models\CustomerMarketplaceItem;

class ProfileRepository
{
    public function __construct()
    {
    }

    public function getShowLists()
    {
        $old_list = [
            "Bid in timed online auctions",
            "Make instant purchases in the Hotlotz Marketplace",
            "Request appraisals using 'Sell With Us'",
            "Manage your consigned items",
            "Pay invoices",
            "Review your digital account preferences"
        ];

        $list = [
            "Update your profile, address book and preferences",
            "Upload enhanced identity information",
            "Request appraisals using our 'Sell With Us' valuation tool",
            "Review items in your 'watchlists'",
            "Sign sales contracts",
            "Manage consigned items",
            "Review invoices and make payments",
            "Review settlement statements",
            "Save payment card and bank account details"
        ];
        return $list;
    }

    public function getShowButtons()
    {
        $card_complete = '';
        $bank_complete = '';
        $invoice_complete = '';
        $preference_complete = '';
        $agreement_complete = '';
        $profile_complete = '';
        $complete_status = '';

        if (Auth::guard('customer')->user()->credit_cards == null) {
            $card_complete = 'complete';
            $complete_status = 'complete';
        }
        if (Auth::guard('customer')->user()->bank_account_number == null &&
            Auth::guard('customer')->user()->bank_name == null &&
            Auth::guard('customer')->user()->bank_account_name == null) {
            $bank_complete = 'complete';
            $complete_status = 'complete';
        }
        if (Auth::guard('customer')->user()->awaiting_payment_count > 0) {
            $invoice_complete = 'complete';
            $complete_status = 'complete';
        }
        if (Auth::guard('customer')->user()->customer_preference_status == null) {
            $preference_complete = 'complete';
            $complete_status = 'complete';
        }
        if (Auth::guard('customer')->user()->selller_agreement_count > 0) {
            $agreement_complete = 'complete';
            $complete_status = 'complete';
        }
        if (Auth::guard('customer')->user()->title == null ||
            Auth::guard('customer')->user()->firstname == null ||
            Auth::guard('customer')->user()->lastname == null ||
            Auth::guard('customer')->user()->email == null ||
            Auth::guard('customer')->user()->phone == null) {
            $profile_complete = 'complete';
            $complete_status = 'complete';
        }

        $btn = collect([
            [
                "btn_text" => "Pay invoice",
                "link" => route('my-receipt', 'awaiting'),
                "complete" => $invoice_complete
            ],
            [
                "btn_text" => "Add credit card details",
                "link" => route('my-credit'),
                "complete" => $card_complete
            ],
            [
                "btn_text" => "Add bank account details",
                "link" => route('my-bank'),
                "complete" => $bank_complete
            ],
            [
                "btn_text" => "Register interests",
                "link" => route('my-preference'),
                "complete" => $preference_complete
            ],
            [
                "btn_text" => "Awating Sales Contracts",
                "link" => route('my-paperwork.seller_agreement'),
                "complete" => $agreement_complete
            ],
            [
                "btn_text" => "Complete Profile",
                "link" => route('my-personal'),
                "complete" => $profile_complete
            ]
        ]);

        $data =  array();
        $data['complete_status'] = $complete_status;
        $data['btn_group'] = $btn;

        return $data;
    }

    public function getMyPaperWork($customer_id)
    {
        $marketplace_invoices = CustomerInvoice::where('customer_id', $customer_id)->whereIn('invoice_type', ['auction', 'marketplace'])->where('active', 1)->orderBy('created_at', 'desc')->get();
        // $marketplace_bill = CustomerInvoice::where('customer_id', $customer_id)->where('invoice_type', 'marketplace')->where('type', 'bill')->get();
        // dd($marketplace_bill);
        $data = [];
        if (!$marketplace_invoices->isEmpty()) {
            $first_item_image = '';
            $fist_item_category = '';

            foreach ($marketplace_invoices as $key => $value) {
                // $phpdate = strtotime( $value->invoice_date );
                // $mysqldate = date( 'Y-m-d H:i:s', $phpdate );

                // $conveted_date = date('l, F dS, Y', strtotime($value->invoice_date));
                $conveted_date = date('l d F', strtotime($value->invoice_date));
                $item_count = 0;
                $item_count_str = '';
                $invoice_type = '';
                $type = '';
                $fist_item_image = '';

                if (!empty($value->invoice())) {
                    $item_count = $value->invoice_amount;
                    if ($item_count > 1) {
                        $lose_count = $item_count -1;
                        $item_count_str = $lose_count.' items';
                    } else {
                        $item_count_str = 'Only '. $item_count. ' item';
                    }
                }

                if ($key == 0) {
                    if (!empty($value->items)) {
                        foreach ($value->items as $itemkey=>$itemvalue) {
                            if ($itemkey == 0) {
                                $item_id = $itemvalue->item->id;
                                $item_image = ItemImage::where('item_id', '=', $item_id)->first();
                                $fist_item_image = $item_image->full_path;
                                $fist_item_category = $itemvalue->item->category->name;
                            }
                        }
                    }
                }

                if ($value->invoice_type == 'auction') {
                    $invoice_type = 'Auction';
                } else {
                    $invoice_type = 'Marketplace';
                }

                if ($value->type == 'bill') {
                    $type = 'Bill';
                } else {
                    $type = 'Invoice';
                }

                if ($value->status == 'Paid' || $value->status == 'Awaiting Payment' || $value->status == 'Awaiting Approval') {
                    $data[] = [
                    "invoice_type" => $invoice_type,
                    "type" => $type,
                    "invoice_id" => $value->invoice_id,
                    "auction_id" => $value->auction_id,
                    "invoice_date" => $conveted_date,
                    "created_at" => $value->created_at,
                    "download_file" => $value->url($value->id),
                    "id" => $value->id,
                    "item_count" => $item_count,
                    "item_count_str" => $item_count-1,
                    "fist_item_image" => $fist_item_image,
                    "fist_item_category" => $fist_item_category,
                    "invoice_status" => $value->status
                ];
                }
            }
            // dd('donee');
        }
        $data = collect($data);

        return $data;
    }

    public function getCutomerIntrests($customer_id)
    {
        $customer_interests = CustomerInterests::where('customer_id', $customer_id)->get();

        $data = [];
        if (!$customer_interests->isEmpty()) {
            foreach ($customer_interests as $value) {
                array_push($data, $value->what_we_sell_id);
            }

            // $data = collect($data);
        }
        return $data;
    }

    public function deleteOldIntrests($customer_id)
    {
        $result = CustomerInterests::where('customer_id', $customer_id)->delete();

        return $result;
    }

    public function getCutomerAddress($customer_id)
    {
        $result = DB::table('customer_addresses')
                    ->where('customer_addresses.customer_id', '=', $customer_id)
                    ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                    ->where('addresses.type', '=', 'shipping')
                    ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')
                    ->orderBy('customer_addresses.address_id', 'desc')
                    ->get();

        $address = [];
        if (!$result->isEmpty()) {
            foreach ($result as $key => $value) {
                $country = Country::where('id', '=', $value->country_id)->first();
                $address[] = [
                    'address_id' => $value->address_id,
                    'country_id' => $value->country_id,
                    'country_name' => $country->name,
                    'postal' => $value->postalcode,
                    'city' => $value->city,
                    'address' => $value->address,
                    'firstname' => $value->firstname,
                    'lastname' => $value->lastname,
                    'state' => $value->state,
                    'zip' => $value->zip_code,
                    'phone' => $value->daytime_phone,
                    'address_nick' => $value->address_nickname,
                    'address2' => $value->address2,
                    'instruction' => $value->delivery_instruction,
                    'is_primary' => $value->is_primary
                ];
            }

            $address = collect($address);
        }
        return $address;
    }

    public function getCorrespondenceAddress($customer_id)
    {
        $address = [];
        // $address['country_id'] = "";
        // $address['postal'] = "";
        // $address['city'] = "";
        // $address['address'] = "";
        // $address['firstname'] = "";
        // $address['lastname'] = "";
        // $address['state'] = "";
        // $address['address_id'] = 0;
        // $address['country_name'] = "";
        // $address['client_address_id'] = 0;

        $result = DB::table('customer_addresses')
                    ->where('customer_addresses.customer_id', '=', $customer_id)
                    ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                    ->where('addresses.type', '=', 'correspondence')
                    ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')
                    ->orderBy('customer_addresses.address_id', 'desc')
                    ->first();

        if ($result) {
            $country = Country::where('id', '=', $result->country_id)->first();
            $address['address_id'] = $result->address_id;
            $address['client_address_id'] = 0;
            $address['country_id'] = $result->country_id;
            $address['country_name'] = $country->name;
            $address['postal'] = $result->postalcode;
            $address['city'] = $result->city;
            $address['address'] = $result->address;
            $address['firstname'] = $result->firstname;
            $address['lastname'] = $result->lastname;
            $address['state'] = $result->state;
        }
        // else {
        //     if (Auth::guard('customer')->user()->firstname != null ||
        //     Auth::guard('customer')->user()->lastname != null ||
        //     Auth::guard('customer')->user()->address1 != null ||
        //     Auth::guard('customer')->user()->country_id!= null ||
        //     Auth::guard('customer')->user()->city != null ||
        //     Auth::guard('customer')->user()->postal_code != null ||
        //     Auth::guard('customer')->user()->state != null) {
        //         $country_id = Auth::guard('customer')->user()->country_id;
        //         $country = Country::where('id', '=', $country_id)->first();
        //         $address['address_id'] = 0;
        //         $address['client_address_id'] = 0;
        //         $address['country_id'] = $country_id;
        //         $address['country_name'] = $country->name;
        //         $address['postal'] = Auth::guard('customer')->user()->postal_code;
        //         $address['city'] = Auth::guard('customer')->user()->city;
        //         $address['address'] = Auth::guard('customer')->user()->address1;
        //         $address['firstname'] = Auth::guard('customer')->user()->firstname;
        //         $address['lastname'] = Auth::guard('customer')->user()->lastname;
        //         $address['state'] = Auth::guard('customer')->user()->state;
        //     }
        // }

        return $address;
    }

    public function getMyReceiptByType($customer_id, $type)
    {
        if ($type == 'miscellaneous') {
            $receipt = CustomerInvoice::where('customer_id', '=', $customer_id)->whereIn('invoice_type', ['adhoc', 'withdraw', 'private'])->where('type', '=', 'invoice')->where('active', 1)->orderBy('created_at', 'desc')->get()->reject(function ($invoice) {
                return $invoice->status == 'Awaiting Payment';
            })->groupBy('invoice_id');
        } else {
            if ($type == 'awaiting') {
                $receipt = CustomerInvoice::where('customer_id', '=', $customer_id)->where('type', '=', 'invoice')->where('active', 1)->orderBy('created_at', 'desc')->get()->reject(function ($invoice) {
                    return $invoice->status !== 'Awaiting Payment';
                })->groupBy('invoice_id');
            } else {
                $receipt = CustomerInvoice::where('customer_id', '=', $customer_id)->where('invoice_type', '=', $type)->where('type', '=', 'invoice')->where('active', 1)->orderBy('created_at', 'desc')->get()->reject(function ($invoice) {
                    return $invoice->status == 'Awaiting Payment';
                })->groupBy('invoice_id');
            }
        }
        $data = [];
        if (!$receipt->isEmpty()) {
            $first_item_image = '';
            $fist_item_category = '';

            foreach ($receipt as $key => $items) {
                foreach ($items as $value) {
                    $conveted_date = date('l d F', strtotime($value->invoice_date));

                    if ($value->invoice_type == 'marketplace') {
                        $customerItems = CustomerMarketplaceItem::where('customer_invoice_id', $value->id)->get();
                    } else {
                        $customerItems = CustomerInvoiceItem::where('customer_invoice_id', $value->id)->get();
                    }
                    $item_count = 0;
                    $item_count_str = '';
                    $invoice_type = '';
                    $type = '';
                    $fist_item_image = '';

                    if (!empty($customerItems)) {
                        $item_count = $customerItems->count();
                        if ($item_count == 1) {
                            $item_count_str = 'Only '. $item_count. ' item';
                        } else {

                            $item_count_str = $item_count.' items';
                        }
                    }
                    if ($key == $value->invoice_id) {
                        if (!empty($customerItems)) {
                            foreach ($customerItems as $itemkey=>$itemvalue) {
                                if ($itemkey == 0) {
                                    $item_id = $itemvalue->item->id ?? null;
                                    $item_image = ItemImage::where('item_id', '=', $item_id)->first() ?? null;
                                    $fist_item_image = $value->invoice_type == 'adhoc' || $value->invoice_type == 'withdraw' ? asset('images/appshell/logo.png') : $item_image->full_path;
                                    $fist_item_category = $itemvalue->item->category->name ?? null;
                                }
                            }
                        }
                    }

                    if ($value->invoice_type == 'auction') {
                        $invoice_type = 'Auction';
                    // } elseif ($value->invoice_type == 'adhoc' || $value->invoice_type == 'withdraw' || $value->invoice_type == 'private') {
                    //     $invoice_type = 'Miscellaneous';
                    } elseif ($value->invoice_type == 'withdraw') {
                        $invoice_type = 'Withdraw';
                    } elseif ($value->invoice_type == 'adhoc') {
                        $invoice_type = 'Adhoc';
                    } elseif ($value->invoice_type == 'private') {
                        $invoice_type = 'Private Sale';
                    } else {
                        $invoice_type = 'Marketplace';
                    }

                    if ($value->type == 'bill') {
                        $type = 'Bill';
                    } else {
                        $type = 'Invoice';
                    }

                    if ($value->invoice_type == 'auction') {
                        $fist_item_category = $value->auction->title ?? null;
                    } else {
                        $fist_item_category = null;
                    }

                    $data[] = [
                    "invoice_type" => $invoice_type,
                    "type" => $type,
                    "invoice_id" => $value->invoice_id,
                    "auction_id" => $value->auction_id,
                    "invoice_date" => $conveted_date,
                    "created_at" => $value->created_at,
                    "download_file" => $value->url($value->id),
                    "id" => $value->id,
                    "item_count" => $item_count,
                    "item_count_str" => $value->invoice_type == 'adhoc' || $value->invoice_type == 'withdraw' || $value->invoice_type == 'private' ? '' : $item_count_str,
                    "fist_item_image" =>  $value->invoice_type == 'adhoc' || $value->invoice_type == 'withdraw' || $value->invoice_type == 'private' ? asset('images/appshell/logo.png') : $fist_item_image,
                    "fist_item_category" => $fist_item_category,
                    "invoice_status" => $value->status,
                    "invoice_processing" => $value->payment_processing
                ];
                }
            }
        }

        $data = collect($data);

        return $data;
    }

    public function getMySettlement($customer_id)
    {
        $receipt = CustomerInvoice::where('customer_id', '=', $customer_id)->where('type', '=', 'bill')->where('active', 1)->orderBy('created_at', 'desc')->get()->groupBy('invoice_id');
        // dd($receipt);
        $data = [];
        if (!$receipt->isEmpty()) {
            $first_item_image = '';
            $fist_item_category = '';

            foreach ($receipt as $key => $items) {
                foreach ($items as $value) {
                    if ($value->status == 'Paid') {
                        $conveted_date = date('l d F', strtotime($value->invoice_date));
                        $customerItems = $value->items;

                        $item_count = 0;
                        $item_count_str = '';
                        $invoice_type = '';
                        $type = '';
                        $fist_item_image = '';

                        if (!empty($customerItems)) {
                            $item_count = $customerItems->count();
                            if ($item_count == 1) {
                                $item_count_str = 'Only '. $item_count. ' item';
                            } else {
                                $item_count_str = $item_count.' items';
                            }
                        }

                        if ($key == $value->invoice_id) {
                            if (!empty($customerItems)) {
                                foreach ($customerItems as $itemkey=>$itemvalue) {
                                    if ($itemkey == 0) {
                                        $item_id = $itemvalue->item->id;
                                        $item_image = ItemImage::where('item_id', '=', $item_id)->first();
                                        $fist_item_image = $item_image->full_path;
                                        $fist_item_category = $itemvalue->item->category->name ?? null;
                                    }
                                }
                            }
                        }

                        if ($value->invoice_type == 'auction') {
                            $invoice_type = 'Auction';
                            $fist_item_category = $value->auction->title ?? null;
                        } elseif ($value->invoice_type == 'adhoc' || $value->invoice_type == 'withdraw' || $value->invoice_type == 'private') {
                            $invoice_type = 'Miscellaneous';
                            $fist_item_category = null;
                        } else {
                            $invoice_type = 'Marketplace';
                            $fist_item_category = 'Sold in the Hotlotz Marketplace';
                        }

                        if ($value->type == 'bill') {
                            $type = 'Bill';
                        } else {
                            $type = 'Invoice';
                        }

                        $data[] = [
                            "invoice_type" => $invoice_type,
                            "type" => $type,
                            "invoice_id" => $value->invoice_id,
                            "auction_id" => $value->auction_id,
                            "invoice_date" => $conveted_date,
                            "created_at" => $value->created_at,
                            // "download_file" => $value->url($value->id),
                            "settlement_id" => $value->id,
                            "item_count" => $item_count,
                            "item_count_str" => $item_count_str,
                            "fist_item_image" => $fist_item_image,
                            "fist_item_category" => $fist_item_category,
                            "invoice_status" => $value->status
                        ];
                    }
                }
            }
        }
        $data = collect($data);

        return $data;
    }

    public function getMyConsignmentItems($customer_id, $status = null)
    {
        $items = Item::where('items.customer_id', '=', $customer_id);
            // ->leftJoin('item_images', function ($join) {
            //     $join->on('item_images.id', '=', DB::raw('
            //         (SELECT item_images.id FROM item_images
            //         WHERE item_images.item_id = items.id
            //         and item_images.deleted_at is NULL
            //         LIMIT 1)'));
            // })
            // ->leftJoin('item_lifecycles', function ($join) {
            //     $join->on('item_lifecycles.id', '=', DB::raw('
            //         (SELECT item_lifecycles.id FROM item_lifecycles
            //         WHERE item_lifecycles.item_id = items.id
            //         and item_lifecycles.type = LOWER(items.lifecycle_status)
            //         and item_lifecycles.deleted_at is NULL
            //         LIMIT 1)'));
            // });


        if($status != null && $status == 'sold_in_auction'){
            $items->whereIn('items.status', [Item::_SOLD_, Item::_PAID_]);
            $items->where('items.lifecycle_status', Item::_AUCTION_);
        }
        if($status != null && $status == 'sold_in_marketplace'){
            $items->whereIn('items.status', [Item::_SOLD_, Item::_PAID_]);
            $items->where(function ($query2) {
                $query2->where('items.lifecycle_status', '!=', Item::_AUCTION_);
                $query2->orWhere('items.lifecycle_status', null);
            });
        }
        if($status != null && $status != 'sold_in_auction' && $status != 'sold_in_marketplace' && $status != 'unsold_in_storage' && $status != 'unsold_dispatched'){
            if($status == Item::_PENDING_){
                $items->whereIn('items.status', [Item::_PENDING_, Item::_PENDING_IN_AUCTION_]);
            }else{
                $items->where('items.status', $status);
            }
        }
        if($status != null && $status == 'unsold_in_storage'){
            $items->where('items.status', Item::_UNSOLD_);
            $items->where('items.tag', 'in_storage');
        }
        if($status != null && $status == 'unsold_dispatched'){
            $items->where('items.status', Item::_UNSOLD_);
            $items->where('items.tag', 'dispatched');
        }

        $myconsignmentItems = $items->select('items.*')
            // , 'item_images.file_name', 'item_images.full_path as item_image_path', 'item_lifecycles.price', 'item_lifecycles.status as item_lifecycle_status')
            ->orderBy('items.created_at', 'desc')
            ->get();
        // dd($myconsignmentItems->toSql(), $myconsignmentItems->getBindings());

        // dd($myconsignmentItems);

        $data = [];
        if (!$myconsignmentItems->isEmpty()) {
            foreach ($myconsignmentItems as $key => $value) {
                $status = '';
                $sold_price = '';
                $link = null;
                $photo = \App\Modules\Item\Models\ItemImage::where('item_id',$value->id)->first();

                $status = $value->status;

                if ($status == Item::_SWU_) {
                    $status = 'UNDER REVIEW';
                }

                if ($status == Item::_PENDING_ || $status == Item::_PENDING_IN_AUCTION_) {
                    $status = 'BEING PREPARED FOR SALE';
                }

                if ($status == Item::_SOLD_ || $status == Item::_PAID_) {
                    $sold_price = '$'.$value->sold_price.' SGD';
                    if($value->lifecycle_status == 'Auction'){
                        $status = 'SOLD IN AUCTION';
                        $auctionItem = AuctionItem::where('item_id', $value->id)->first();
                        $auction = Auction::find($auctionItem->auction_id);
                        if($auction && $auction != null && $auction->sr_reference != null){
                            $link = "https://" . config('thesaleroom.atg_tenant_id'). "/past-auctions/" .$auction->sr_reference;
                        }
                    }else{
                        $status = 'SOLD IN MARKETPLACE';
                    }
                }

                $bill_id = 0;
                if ($status == Item::_SETTLED_ && $value->bill_id) {
                    $bill = CustomerInvoice::where('invoice_id', $value->bill_id)->first();
                    if($bill && $bill != null && $bill->id != null){
                        $bill_id = $bill->id;
                    }
                }

                if ($status == Item::_IN_AUCTION_) {
                    $status = 'ON SALE IN AUCTION';
                    $auctionItem = AuctionItem::where('item_id', $value->id)->first();
                    $auction = Auction::find($auctionItem->auction_id);
                    if($auction && $auction != null && $auction->lot_id != null){
                        $link = 'https://'. config('thesaleroom.atg_tenant_id') . '/auctions/7613/'.$auction->sr_reference.'/lot-details/'.$auctionItem->lot_id;
                    }
                }

                if ($status == Item::_IN_MARKETPLACE_) {
                    $status = 'ON SALE IN MARKETPLACE';
                    $link = route('marketplace.marketplace-item-detail', ['item_id' => $value->id]);
                }

                if ($status == Item::_DECLINED_) {
                    $status = 'DECLINE';
                }

                if($status != null && $status == Item::_UNSOLD_){
                    if($value->tag == 'in_storage'){
                        $status = 'Unsold - Awaiting Collection';
                    }
                    if($value->tag == 'dispatched'){
                        $status = 'Unsold - Returned';
                    }
                }

                if($status != Item::_ITEM_RETURNED_){
                    $data[] = [
                        'photoPath' => ($photo)? $photo->full_path: null,
                        'name' => $value->name,
                        'status' => $status,
                        'sold_price' => $sold_price,
                        'bill_id' => $bill_id,
                        'link' => $link
                    ];
                }
            }
        }
        $data = collect($data);

        return $data;
    }

    public function getCustomerDocumentData($id, $type = 'document')
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
                'url'=>'/additional_info/'.$cust_document->id.'/document_delete',
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

    public function getKycAddress($customer_id)
    {
        $address = DB::table('customer_addresses')
                ->where('customer_addresses.customer_id', '=', $customer_id)
                ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                ->where('addresses.type', '=', 'kyc')
                ->select('addresses.*')
                ->first();
        return $address;
    }

    public function getCountKycAddress($customer_id)
    {
        $count_kyc_address = DB::table('customer_addresses')
                ->where('customer_addresses.customer_id', '=', $customer_id)
                ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                ->where('addresses.type', 'kyc')
                ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')
                ->count();

        return $count_kyc_address;
    }

    public function getCutomerAddressForKyc($customer_id)
    {
        $cor_address = DB::table('customer_addresses')
                    ->where('customer_addresses.customer_id', '=', $customer_id)
                    ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                    ->where('addresses.type', '=', 'correspondence')
                    ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')                    
                    ->first();

        $address = [];
        $exist_cor_address = 'N';
        if ($cor_address) {
            $exist_cor_address = 'Y';
            $country = Country::where('id', '=', $cor_address->country_id)->first();
            $address[] = [
                'address_id' => $cor_address->address_id,
                'country_id' => $cor_address->country_id,
                'type' => $cor_address->type,
                'country_name' => $country->name,
                'postal' => $cor_address->postalcode,
                'city' => $cor_address->city,
                'address' => $cor_address->address,
                'firstname' => $cor_address->firstname,
                'lastname' => $cor_address->lastname,
                'state' => $cor_address->state,
                'zip' => $cor_address->zip_code,
                'phone' => $cor_address->daytime_phone,
                'address_nick' => $cor_address->address_nickname,
                'address2' => $cor_address->address2,
                'instruction' => $cor_address->delivery_instruction,
                'is_primary' => 1,
            ];
        }

        $result = DB::table('customer_addresses')
                ->where('customer_addresses.customer_id', '=', $customer_id)
                ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
                ->where('addresses.type', '!=', 'correspondence')
                ->select('addresses.*', 'customer_addresses.*', 'addresses.id as address_id')
                ->orderBy('customer_addresses.address_id', 'desc')
                ->get();
        // dd($result->toArray());

        
        if (!$result->isEmpty()) {
            foreach ($result as $key => $value) {
                $country = Country::where('id', '=', $value->country_id)->first();
                $address[$key+1] = [
                    'address_id' => $value->address_id,
                    'country_id' => $value->country_id,
                    'type' => $value->type,
                    'country_name' => $country->name,
                    'postal' => $value->postalcode,
                    'city' => $value->city,
                    'address' => $value->address,
                    'firstname' => $value->firstname,
                    'lastname' => $value->lastname,
                    'state' => $value->state,
                    'zip' => $value->zip_code,
                    'phone' => $value->daytime_phone,
                    'address_nick' => $value->address_nickname,
                    'address2' => $value->address2,
                    'instruction' => $value->delivery_instruction,
                    'is_primary' => ($exist_cor_address == 'Y')? 0:$value->is_primary,
                ];
            }
        }

        $address = collect($address);
        return $address;
    }
}
