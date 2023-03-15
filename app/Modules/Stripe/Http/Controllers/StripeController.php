<?php

namespace  App\Modules\Stripe\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StripePayload;
use App\Events\ItemHistoryEvent;
use App\Modules\Item\Models\Item;
use App\Jobs\Item\CheckoutTimeOut;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\ItemRepository;
use Illuminate\Support\Facades\Auth;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Models\ItemLifecycle;
use App\Events\Xero\XeroPaidedInvoiceEvent;
use XeroAPI\XeroPHP\AccountingObjectSerializer;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\OrderSummary\Models\OrderSummary;
use App\Events\Xero\CreateMarketPlaceInvoiceEvent;
use App\Events\Admin\MarketplaceSoldItemListEmailEvent;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\OrderSummary\Http\Repositories\OrderSummaryRepository;
use App\Events\Admin\BankTransferPaynowCheckoutAlertEvent;

class StripeController extends Controller
{
    public function loadForm(OrderSummaryRepository $orderSummaryRepository, ItemRepository $itemRepository, Request $request)
    {
        $customer = Customer::findOrFail($request->hid_customer_id);
        $items = json_decode($request->hid_item_arr_id, true);
        $checkShip = $request->chk_ship;
        $addressId = $request->is_primary;

        $total_price = 0;
        if ($request->invoice_id) {
            $items['items'] = [];
            $customerInvoice = CustomerInvoice::where('invoice_id', $request->invoice_id)->first();
            $total_price = $customerInvoice->invoice_amount;
        } else {
            if ($items !== null) {
                foreach ($items['items'] as $itemDetail) {
                    $getItemDetail = $itemRepository->getItemDetail($itemDetail['id']);
                    $total_price += $getItemDetail->price;
                }
            }
        }

        if ($items == null) {
            return redirect()->route('marketplace.item-checkout')->withError('Your shopping bag is empty. Please add item(s) to your bag before checking out. Thank you.');
        }

        if ($request->payment_type == 'bank' && $request->invoice_id || $request->payment_type == 'paynow' && $request->invoice_id) {
            if ($customerInvoice->invoice_type == 'auction') {
                $orderSummary['invoice_id'] = $customerInvoice->invoice_id;

                $orderSummary['customer_id'] = $customerInvoice->customer_id;
                $orderSummary['total'] = $customerInvoice->invoice_amount;
                $orderSummary['from'] = 'auction';
                $orderSummary['type'] = $checkShip == 'yes' ? 'ship' : 'pickup';
                $orderSummary['status'] = OrderSummary::PENDING;

                if ($checkShip == 'yes') {
                    $orderSummary['address_id'] = $addressId;
                }

                $order = $orderSummaryRepository->create($orderSummary);

                foreach ($customerInvoice->items as $customerInvoiceItem) {
                    $item = $customerInvoiceItem->item;
                    if ($checkShip == 'yes') {
                        $item->delivery_requested = "Y";
                        $item->delivery_requested_date = date('Y-m-d H:i:s');
                    }
                    $item->save();

                    $order->items()->attach([$item->id]);
                }
            }

            $customerInvoice->payment_processing = 1;
            $customerInvoice->payment_type = $request->payment_type;
            $customerInvoice->save();

            $type = $customerInvoice->invoice_type;
            if (in_array($customerInvoice->invoice_type, ['private', 'withdraw', 'adhoc'])) {
                $type = 'miscellaneous';
            }

            \Log::info('Bank Transfer/Paynow Checkout Alert '.$customer->ref_no);
            $invoice_url = $customerInvoice->url($request->invoice_id);
            $invoice_number = $customerInvoice->invoice_number;
            event( new BankTransferPaynowCheckoutAlertEvent($customer->id, $invoice_number, $invoice_url) );

            return redirect()->route('my-receipt-checkout-final');
        }

        $data = [
            'items' => $items['items'],
            'total_price' => $total_price,
            'customer_id' => $request->hid_customer_id,
            'is_save_card' => $request->is_save_card,
            'payment_type' => $request->payment_type,
            'invoice_id' => $request->invoice_id,
            'shipType' => $checkShip,
            'addressId' => $addressId
        ];

        if ($request->payment_type == 'new' || $customer->stripe_customer_id == null) {
            $jobPayload['customer_id'] = $request->hid_customer_id;
            $jobPayload['items'] = $items['items'];
            dispatch((new CheckoutTimeOut($jobPayload))->onQueue('checkout'))->delay(now()->addMinutes(15));

            return view('stripe::index', compact('data'));
        } else {
            try {
                Stripe::setApiKey(setting('services.stripe.secret'));
                //charges

                if ($data['invoice_id']) {
                    $idempotencyKey = $data['invoice_id'];
                } else {
                    $arrayHash = [
                        'items' => $items['items'],
                        'total_price' => $total_price,
                        'customer_id' => $request->hid_customer_id,
                    ];

                    $idempotencyKey = md5(serialize($arrayHash));
                }

                $payment_intent = \Stripe\PaymentIntent::create(
                    [
                    'amount' => $total_price * 100,
                    'currency' => 'sgd',
                    'payment_method' => $request->payment_type,
                    'customer' => $customer->stripe_customer_id,
                    'confirm' => true,
                    'off_session' => true
                    ],
                    [
                        'idempotency_key' => $idempotencyKey . '-' . date('YmdHi')
                    ]
                );
                \Log::channel('stripelog')->info('Success Direct Charge Worked');
                \Log::channel('stripelog')->info('======= Payload - Success Direct Charge '. print_r($payment_intent, true) .'=======');

                $data['payment_intent'] =  $payment_intent->id;
                $data['payment_type'] =  $request->payment_type;

                $customer_id = $data['customer_id'];
                if ($data['invoice_id']) {
                    $date = Carbon::now();
                    $customerInvoice = CustomerInvoice::where('invoice_id', $data['invoice_id'])->first();
                    if ($customerInvoice->invoice_type == 'auction') {
                        $customerInvoiceItems = CustomerInvoiceItem::where('customer_invoice_id', $customerInvoice->id)->get();

                        foreach ($customerInvoiceItems as $customerInvoiceItem) {
                            $item_id = $customerInvoiceItem->item_id;

                            $itemModel = $customerInvoiceItem->item;

                            $itemModel->updateRelatedItemStatus($itemModel, $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_, $customer_id, $customerInvoice->invoice_type, $customerInvoice->auction_id);
                        }
                    }

                    $customerInvoice->payment_processing = 1;
                    $customerInvoice->payment_type = 'stripe';
                    $customerInvoice->save();

                    $stripePayload = new StripePayload;
                    $stripePayload->customer_id = $customer_id;
                    $stripePayload->invoice_id = $data['invoice_id'];
                    $stripePayload->event = '\App\Events\Xero\XeroPaidedInvoiceEvent';
                    $stripePayload->payload = json_encode($data);
                    $stripePayload->save();

                    event(new XeroPaidedInvoiceEvent($data));

                    $stripePayload->pass = 1;
                    $stripePayload->save();

                    $type = $customerInvoice->invoice_type;
                    if (in_array($customerInvoice->invoice_type, ['private', 'withdraw', 'adhoc'])) {
                        $type = 'miscellaneous';
                    }

                    return redirect()->route('my-receipt-checkout-success');
                } else {
                    $date = Carbon::now();

                    foreach ($data['items'] as $item) {
                        $item_id = $item['id'];
                        $itemModel = Item::findOrFail($item_id);
                        $getItemDetail = $itemRepository->getItemDetail($item_id);
                        $itemPrice = $getItemDetail->price;
                        $item_data = [
                            'buyer_id'      => $customer_id,
                            'status'             => $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_,
                            'storage_date'      => $date,
                            'tag'      => 'in_storage',
                        ];
                        if ($itemModel->is_hotlotz_own_stock == 'Y') {
                            $item_data['settled_date'] = $date;
                        }
                        if ($itemModel->is_hotlotz_own_stock != 'Y') {
                            $item_data['paid_date'] = $date;
                        }
                        Item::where('id', $item_id)->update($item_data);

                        ItemLifecycle::where('item_id', $item_id)->where('type', strtolower($itemModel->lifecycle_status))->update(
                            [
                                'buyer_id'      => $customer_id,
                                'status'             => $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_,
                                'action' => ItemLifecycle::_FINISHED_
                            ]
                        );

                        $skip_lifecycle = [
                            'action'=>ItemLifecycle::_SKIPPED_,
                        ];
                        ItemLifecycle::where('item_id', $item_id)->where('type', '!=', 'storage')->where('type', '!=', strtolower($itemModel->lifecycle_status))->whereNull('action')->update($skip_lifecycle);

                        $storage_lifecycle = [
                            'action'=>ItemLifecycle::_PROCESSING_,
                            'entered_date'=>$date,
                        ];
                        ItemLifecycle::where('type', 'storage')->where('item_id', $item_id)->update($storage_lifecycle);

                        $this->addItemHistory(Item::where('id', $item_id)->first(), ItemLifecycle::where('item_id', $item_id)->where('type', strtolower($itemModel->lifecycle_status))->where('status', $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_)->first());
                    }

                    $stripePayload = new StripePayload;
                    $stripePayload->customer_id = $customer_id;
                    $stripePayload->event = '\App\Events\Xero\CreateMarketPlaceInvoiceEvent';
                    $stripePayload->payload = json_encode($data);
                    $stripePayload->save();

                    \Log::channel('emailLog')->info('call MarketplaceSoldItemListEmailEvent');
                    event(new MarketplaceSoldItemListEmailEvent($data));
                    event(new CreateMarketPlaceInvoiceEvent($data));

                    $stripePayload->pass = 1;
                    $stripePayload->save();

                    $payload['items'] = $data['items'];
                    $payload['transaction'] = $data['payment_intent'];
                    $payload['revenue'] = 0.00;
                    $payload['tax'] = 0.00;
                    $payload['shipping'] = null;

                    session(['checkout-final' => $payload]);

                    return redirect()->route('marketplace.item-checkout-final');
                }
            } catch (\XeroAPI\XeroPHP\ApiException $e) {
                $str = $e->getMessage();

                \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

                if ($data['invoice_id']) {
                    return redirect()->route('my-receipt-checkout', $data['invoice_id'])->withError('Payment failed. Please try using a different card or different payment method');
                }

                return redirect()->route('marketplace.item-checkout')->withError('Payment failed. Please try using a different card or different payment method');
            } catch (\throwable $e) {
                $error  = '';
                $error .= 'Status is:' . 'Something else happened, completely unrelated to Stripe' . '\n';
                $error .= 'Message is:' . $e->getMessage() . '\n';

                \Log::channel('stripelog')->error($error);

                if ($data['invoice_id']) {
                    return redirect()->route('my-receipt-checkout', $data['invoice_id'])->withError('Payment failed. Please try using a different card or different payment method');
                }
                if (isset($data['items'])) {
                    foreach ($data['items'] as $item) {
                        $item_id = $item['id'];
                        Item::where('id', $item_id)->update(
                            [
                                'buyer_id'      => null,
                                'status'        => Item::_IN_MARKETPLACE_,
                                'sold_price' => null,
                                'sold_date' => null,
                                'storage_date' => null,
                                'tag' => null,
                            ]
                        );

                        ItemLifecycle::where('item_id', $item_id)->update(
                            [
                                'buyer_id'      => null,
                                'status'        => null,
                                'sold_price' => null,
                                'sold_date' => null
                            ]
                        );
                    }
                }

                return redirect()->route('marketplace.item-checkout')->withError('Payment failed. Please try using a different card or different payment method');
            }
        }
    }

    public function success(XeroInvoiceRepository $xeroInvoiceRepository, OauthCredentialManager $xeroCredentials, AccountingApi $apiInstance, ItemRepository $itemRepository)
    {
        try {
            $payload = session(request('session_id'));

            \Log::channel('stripelog')->info('Success Call Back Worked');
            \Log::channel('stripelog')->info('======= Payload - Success Call Back '. print_r($payload, true) .'=======');

            $customer_id = $payload['customer_id'];
            $stripeSession = $payload['stripe_session_id'];

            Stripe::setApiKey(setting('services.stripe.secret'));
            $stripe = \Stripe\Checkout\Session::retrieve(
                $stripeSession,
                []
            );

            $stripeClient = new \Stripe\StripeClient(
                setting('services.stripe.secret')
            );
            $retrivePaymentIntent = $stripeClient->paymentIntents->retrieve(
                $stripe->payment_intent,
                []
            );

            if ($payload['is_save_card']) {
                $customer = Customer::findOrFail($customer_id);
                $customer->stripe_customer_id = $stripe->customer;
                $customer->save();
                $stripeClient->paymentMethods->update(
                    $retrivePaymentIntent->payment_method,
                    ['metadata' => ['is_save' => true]]
                );
            }

            $payload['payment_type'] = $retrivePaymentIntent->payment_method;
            $payload['payment_intent'] = $stripe->payment_intent;

            if ($payload['invoice_id']) {
                $date = Carbon::now();
                $customerInvoice = CustomerInvoice::where('invoice_id', $payload['invoice_id'])->first();
                if ($customerInvoice->invoice_type == 'auction') {
                    $customerInvoiceItems = CustomerInvoiceItem::where('customer_invoice_id', $customerInvoice->id)->get();
                    foreach ($customerInvoiceItems as $customerInvoiceItem) {
                        $item_id = $customerInvoiceItem->item_id;

                        $itemModel = $customerInvoiceItem->item;

                        $itemModel->updateRelatedItemStatus($itemModel, $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_, $customer_id, $customerInvoice->invoice_type, $customerInvoice->auction_id);
                    }
                }

                $customerInvoice->payment_processing = 1;
                $customerInvoice->payment_type = 'stripe';
                $customerInvoice->save();

                $stripePayload = new StripePayload;
                $stripePayload->customer_id = $customer_id;
                $stripePayload->invoice_id = $payload['invoice_id'];
                $stripePayload->event = '\App\Events\Xero\XeroPaidedInvoiceEvent';
                $stripePayload->payload = json_encode($payload);
                $stripePayload->save();

                event(new XeroPaidedInvoiceEvent($payload));

                $stripePayload->pass = 1;
                $stripePayload->save();

                session()->forget(request('session_id'));

                $type = $customerInvoice->invoice_type;
                if (in_array($customerInvoice->invoice_type, ['private', 'withdraw', 'adhoc'])) {
                    $type = 'miscellaneous';
                }
                return redirect()->route('my-receipt-checkout-success');
            }

            $date = Carbon::now();
            // $solddate = $date->toDateTimeString();
            foreach ($payload['items'] as $item) {
                $item_id = $item['id'];

                $itemModel = Item::findOrFail($item_id);
                $getItemDetail = $itemRepository->getItemDetail($item_id);
                $itemPrice = $getItemDetail->price;
                $item_data = [
                    'buyer_id'      => $customer_id,
                    'status'        => $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_,
                    'storage_date'      => $date,
                    'tag'      => 'in_storage',
                ];
                if ($itemModel->is_hotlotz_own_stock == 'Y') {
                    $item_data['settled_date'] = $date;
                }
                if ($itemModel->is_hotlotz_own_stock != 'Y') {
                    $item_data['paid_date'] = $date;
                }
                Item::where('id', $item_id)->update($item_data);

                ItemLifecycle::where('item_id', $item_id)->where('type', strtolower($itemModel->lifecycle_status))->update(
                    [
                    'buyer_id'      => $customer_id,
                    'status'        => $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_,
                    'action' => ItemLifecycle::_FINISHED_
                    ]
                );

                $skip_lifecycle = [
                    'action'=>ItemLifecycle::_SKIPPED_,
                ];
                ItemLifecycle::where('item_id', $item_id)->where('type', '!=', 'storage')->where('type', '!=', strtolower($itemModel->lifecycle_status))->whereNull('action')->update($skip_lifecycle);

                $storage_lifecycle = [
                    'action'=>ItemLifecycle::_PROCESSING_,
                    'entered_date'=>$date,
                ];
                ItemLifecycle::where('type', 'storage')->where('item_id', $item_id)->update($storage_lifecycle);

                $this->addItemHistory(Item::where('id', $item_id)->first(), ItemLifecycle::where('item_id', $item_id)->where('type', strtolower($itemModel->lifecycle_status))->where('status', $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_)->first());
            }

            $stripePayload = new StripePayload;
            $stripePayload->customer_id = $customer_id;
            $stripePayload->event = '\App\Events\Xero\CreateMarketPlaceInvoiceEvent';
            $stripePayload->payload = json_encode($payload);
            $stripePayload->save();

            \Log::channel('emailLog')->info('call MarketplaceSoldItemListEmailEvent');
            event(new MarketplaceSoldItemListEmailEvent($payload));
            event(new CreateMarketPlaceInvoiceEvent($payload));

            $stripePayload->pass = 1;
            $stripePayload->save();

            session()->forget(request('session_id'));

            $data['items'] = $payload['items'];
            $data['transaction'] = $payload['payment_intent'];
            $data['revenue'] = 0.00;
            $data['tax'] = 0.00;
            $data['shipping'] = null;

            session(['checkout-final' => $data]);

            return redirect()->route('marketplace.item-checkout-final');
        } catch (\XeroAPI\XeroPHP\ApiException $e) {
            $str = $e->getMessage();

            \Log::channel('stripelog')->error('Success Call Back Failed By Xero');

            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            if ($payload['invoice_id']) {
                return redirect()->route('my-receipt-checkout', $payload['invoice_id'])->withError('Whoops! ' . $str);
            }

            return redirect()->route('marketplace.item-checkout')->withError('Whoops! ' . $str);
        } catch (\Throwable $e) {
            $error  = '';
            $error .= 'Status is:' . 'Something else happened, completely unrelated to Stripe' . '\n';
            $error .= 'Message is:' . $e->getMessage() . '\n';
            \Log::channel('stripelog')->error('Success Call Back Failed');
            \Log::channel('stripelog')->error($error);

            if ($payload['invoice_id']) {
                return redirect()->route('my-receipt-checkout', $payload['invoice_id'])->withError('Whoops! ' . $e->getMessage());
            }

            return redirect()->route('marketplace.item-checkout')->withError('Payment failed. Please try using a different card or different payment method');
        }
    }

    public function cancel()
    {
        $payload = session(request('session_id'));

        if ($payload['invoice_id']) {
            return redirect()->route('my-receipt-checkout', $payload['invoice_id']);
        }

        $customer_id = $payload['customer_id'];
        if (isset($payload['items'])) {
            foreach ($payload['items'] as $item) {
                $item_id = $item['id'];
                Item::where('id', $item_id)->update(
                    [
                        'buyer_id' => null,
                        'status' => Item::_IN_MARKETPLACE_,
                        'sold_price' => null,
                        'sold_date' => null,
                        'storage_date' => null,
                        'tag' => null,
                    ]
                );

                ItemLifecycle::where('item_id', $item_id)->update(
                    [
                        'buyer_id' => null,
                        'status' => null,
                        'sold_price' => null,
                        'sold_date' => null,
                    ]
                );
            }
        }

        session()->forget(request('session_id'));

        return redirect()->route('marketplace.item-checkout');
    }

    public function checkout(Request $request, ItemRepository $itemRepository)
    {
        $payload = $request->all();
        try {
            $lineItems = [];

            if (isset($payload['items'])) {
                foreach ($payload['items'] as $key => $eachItem) {
                    $getItemDetail = $itemRepository->getItemDetail($eachItem['id']);
                    $itemPrice = $getItemDetail->price;
                    $item = Item::findOrFail($eachItem['id']);
                    $photo = \App\Modules\Item\Models\ItemImage::where('item_id', $item->id)->select('full_path')->first();
                    $lineItem =
                    [
                        'price_data' => [
                            'currency' => 'sgd',
                            'product_data' => [
                                'name' => $item->name,
                                'images' => [$photo->full_path],
                            ],
                            'unit_amount' => $itemPrice * 100,
                        ],
                        'quantity' => 1,
                    ];

                    array_push($lineItems, $lineItem);
                }
                if ($request->invoice_id) {
                    $lineItem =
                    [
                        'price_data' => [
                            'currency' => 'sgd',
                            'product_data' => [
                                'name' => $request->invoice_id
                            ],
                            'unit_amount' => $request->total_price * 100,
                        ],
                        'quantity' => 1,
                    ];
                    array_push($lineItems, $lineItem);
                }
            }

            Stripe::setApiKey(setting('services.stripe.secret'));
            $token = Str::random(30);
            $customer = Customer::findOrFail($payload['customer_id']);
            $sessionData = [
                'billing_address_collection' => 'required',
                'customer_email' => $customer->email,
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('stripe.success', ['session_id' => $token]),
                'cancel_url' => route('stripe.cancel', ['session_id' => $token]),
             ];

            if ($payload['is_save_card'] == 'on') {
                $sessionData['payment_intent_data'] = [
                'setup_future_usage' => 'on_session'
                ];
            }

            if ($customer->stripe_customer_id !== null) {
                $sessionData['customer'] = $customer->stripe_customer_id;
                unset($sessionData['customer_email']);
            }

            $session = \Stripe\Checkout\Session::create($sessionData);

            $payload['stripe_session_id'] =  $session->id;

            session([$token => $payload]);

            return response()->json(
                [
                'status' => 200,
                'id' => $session->id
                ]
            );
        } catch (\throwable $e) {
            $error  = '';
            $error .= 'Status is:' . 'Something else happened, completely unrelated to Stripe' . '\n';
            $error .= 'Message is:' . $e->getMessage() . '\n';

            \Log::channel('stripelog')->error($error);

            if (isset($payload['items'])) {
                foreach ($payload['items'] as $item) {
                    $item_id = $item['id'];
                    Item::where('id', $item_id)->update(
                        [
                            'buyer_id'      => null,
                            'status'             => Item::_IN_MARKETPLACE_,
                            'sold_price' => null,
                            'sold_date' => null,
                            'storage_date' => null,
                            'tag' => null,
                        ]
                    );

                    ItemLifecycle::where('item_id', $item_id)->update(
                        [
                            'buyer_id'      => null,
                            'status' => null,
                            'sold_price' => null,
                            'sold_date' => null
                        ]
                    );
                }
            }
            $redirectlink = route('marketplace.item-checkout');
            if ($payload['invoice_id']) {
                $redirectlink = route('my-receipt-checkout', $payload['invoice_id']);
            }
            \Session::flash('error', 'Payment failed. Please try using a different card or different payment method');
            return response()->json(
                [
                'status' => 500,
                'url' => $redirectlink
                ]
            );
        }
    }

    public function charges(Request $request)
    {
        try {
            $customer = Customer::findOrFail($request->customer_id);

            Stripe::setApiKey(setting('services.stripe.secret'));

            $idempotencyKey = $request->invoice_id;
            //charges
            $payment_intent = \Stripe\PaymentIntent::create(
                [
                'amount' => $request->amount * 100,
                'currency' => 'sgd',
                'payment_method' => $request->payment,
                'customer' => $customer->stripe_customer_id,
                'confirm' => true,
                'off_session' => true
                ],
                [
                    'idempotency_key' => $idempotencyKey . '-' . date('YmdHi')
                ]
            );

            $date = Carbon::now();
            $customerInvoice = CustomerInvoice::where('id', $request->invoice_id)->first();
            if ($customerInvoice->invoice_type == 'auction') {
                $customerInvoiceItems = CustomerInvoiceItem::where('customer_invoice_id', $customerInvoice->id)->get();
                foreach ($customerInvoiceItems as $customerInvoiceItem) {
                    $item_id = $customerInvoiceItem->item_id;

                    $itemModel = $customerInvoiceItem->item;

                    $itemModel->updateRelatedItemStatus($itemModel, $itemModel->is_hotlotz_own_stock == 'Y' ? Item::_SETTLED_ : Item::_PAID_, $customer->id, $customerInvoice->invoice_type, $customerInvoice->auction_id);
                }
            }
            $payload['customer_id'] = $customer->id;
            $payload['invoice_id'] = $customerInvoice->invoice_id;
            $payload['payment_intent'] = $payment_intent->id;
            $payload['shipType'] = 'no';
            $payload['payment_type'] = $request->payment;

            $customerInvoice->payment_processing = 1;
            $customerInvoice->payment_type = 'stripe';
            $customerInvoice->save();

            $stripePayload = new StripePayload;
            $stripePayload->customer_id = $customer->id;
            $stripePayload->invoice_id = $customerInvoice->invoice_id;
            $stripePayload->event = '\App\Events\Xero\XeroPaidedInvoiceEvent';
            $stripePayload->payload = json_encode($payload);
            $stripePayload->save();

            event(new XeroPaidedInvoiceEvent($payload));

            $stripePayload->pass = 1;
            $stripePayload->save();

            flash()->success(__('Charges amount is successful', ['name' => $customer->getName()]));

            return response()->json(
                [
                'success' => true
                ]
            );
        } catch (\XeroAPI\XeroPHP\ApiException $e) {
            $str = $e->getMessage();

            \Log::channel('stripelog')->error('Charges Failed By Xero');
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            return response()->json(
                [
                'success' => false
                ]
            );
        } catch (\throwable $e) {
            $error  = '';
            $error .= 'Status is:' . 'Something else happened, completely unrelated to Stripe' . '\n';
            $error .= 'Message is:' . $e->getMessage() . '\n';

            \Log::channel('stripelog')->error($error);

            return response()->json(
                [
                'success' => false
                ]
            );
        }
    }

    public function deleteCard(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(
                setting('services.stripe.secret')
            );
            $stripe->paymentMethods->detach(
                $request->source_card,
                []
            );

            return redirect()->back()->with('success', 'Thank you, your payment details have been updated');
            ;
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    protected function addItemHistory($updated_item, $current_item_lifecycle)
    {
        $sold_price_inclusive_gst = $updated_item->sold_price;
        $sold_price_exclusive_gst = ($updated_item->sold_price / 1.08);

        ##for Item Sold Noti Email Schedule
        $item_history = [
            'item_id' => $updated_item->id,
            'customer_id' => $updated_item->customer_id,
            'buyer_id' => $updated_item->buyer_id,
            'auction_id' => null,
            'item_lifecycle_id' => $current_item_lifecycle->id,
            'price' => $current_item_lifecycle->price,
            'sold_price' => $updated_item->sold_price,
            'sold_price_inclusive_gst' => $sold_price_inclusive_gst,
            'sold_price_exclusive_gst' => $sold_price_exclusive_gst,
            'type' => 'marketplace',
            'status' => Item::_SOLD_,
            'entered_date' => Carbon::now(),
            'specific_date' => Carbon::now(),
        ];
        \Log::channel('lifecycleLog')->info('call ItemHistoryEvent -  Sold item');
        event(new ItemHistoryEvent($item_history));

        ##for Storage Noti Email Schedule when Item Sold
        $storage_item_lifecycle = ItemLifecycle::where('type', 'storage')->where('item_id', $updated_item->id)->first();

        $item_history = [
            'item_id' => $updated_item->id,
            'customer_id' => null,//need to set null for Sold-Storage History
            'buyer_id' => $updated_item->buyer_id,
            'auction_id' => null,
            'item_lifecycle_id' => $storage_item_lifecycle->id,
            'price' => $storage_item_lifecycle->price,
            'type' => 'lifecycle',
            'status' => Item::_STORAGE_,
            'entered_date' => Carbon::now(),
        ];
        \Log::channel('lifecycleLog')->info('call ItemHistoryEvent -  Sold item');
        event(new ItemHistoryEvent($item_history));
    }
}
