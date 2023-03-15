<?php

namespace App\Modules\Xero\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Modules\Xero\Models\XeroItem;
use App\Modules\Auction\Models\Auction;
use App\Modules\Item\Models\AuctionItem;
use App\Modules\Xero\Models\XeroInvoice;
use App\Modules\Xero\Models\XeroTracking;
use XeroAPI\XeroPHP\Models\Accounting\TaxType;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use App\Modules\Customer\Models\CustomerMarketplaceItem;
use App\Modules\Xero\Accounting\Automate as AccountingAutomate;

class XeroControlRepository
{
    protected $accountingAutomate;

    public function __construct(AccountingAutomate $accountingAutomate)
    {
        $this->accountingAutomate = $accountingAutomate;
    }

    public function saveAuctionInvoice($payload)
    {
        XeroInvoice::firstOrCreate(
            [
                'type' => 'auction',
                'buyer_id' => $payload['buyer_id'],
                'seller_id' => $payload['seller_id'],
                'item_id' =>  $payload['item_id'],
                'auction_id' => $payload['auction_id'],
                'price' => $payload['hammer_price'],
                'sold_price_inclusive_gst' => $payload['sold_price_inclusive_gst'],
                'sold_price_exclusive_gst' => $payload['sold_price_exclusive_gst'],
            ],
            [
            'id' => (string) Str::uuid(),
            'type' => 'auction',
            'buyer_id' => $payload['buyer_id'],
            'seller_id' => $payload['seller_id'],
            'item_id' =>  $payload['item_id'],
            'auction_id' => $payload['auction_id'],
            'price' => $payload['hammer_price'],
            'sold_price_inclusive_gst' => $payload['sold_price_inclusive_gst'],
            'sold_price_exclusive_gst' => $payload['sold_price_exclusive_gst'],
            ]
        );

        return 'Success Save Auction Xero Invoice For ' . $payload['auction_id'];
    }


    public function saveMarketPlaceInvoice($payload)
    {
        foreach ($payload['items'] as $eachItem) {
            XeroInvoice::firstOrCreate(
                [
                    'type' => 'marketplace',
                    'buyer_id' => $payload['customer_id'],
                    'item_id' =>  $eachItem['id'],
                    'price' => $eachItem['price']
                ],
                [
                    'id' => (string) Str::uuid(),
                    'type' => 'marketplace',
                    'buyer_id' => $payload['customer_id'],
                    'item_id' =>  $eachItem['id'],
                    'price' => $eachItem['price']
                ]
            );
        }

        return 'Success Save Marketplace Xero Invoice For ' . $payload['customer_id'];
    }

    public function setXeroContact($contactId)
    {
        $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
        $contact->setContactId($contactId);

        return $contact;
    }

    public function getSellerLineItems($price, $item, $seller, $itemFrom = 'auction', $sellerType = null)
    {
        $lineItems = [];
        if ($itemFrom == 'auction') {
            array_push($lineItems, $this->autionSellerHammer(number_format($price / 1.08, 4, '.', ''), $item, 'NONE'));//default none
        } elseif ($itemFrom == 'private' && $sellerType == 'private') {
            array_push($lineItems, $this->privateSellerPrice(number_format($price / 1.08, 4, '.', ''), $item, 'NONE', $itemFrom));//default none
        } else {
            array_push($lineItems, $this->marketplaceSellerAccountCode(number_format($price / 1.08, 4, '.', ''), $item, 'NONE'));//default none
        }

        $taxType = TaxType::ZERORATEDOUTPUT;

        if ($seller->buyer_gst_status == 1) {
            $taxType = "OUTPUTY23";
        }

        if ($item->fee_type == 'fixed_cost_sales_fee') {
            $fee = (float) str_replace('$', '', $item->fee_structure->fixed_cost_sales_fee);
            array_push($lineItems, $this->fixedCostSale($fee, $item, $itemFrom, $taxType));
        }

        if ($item->fee_type == 'sales_commission') {
            $commission = (float) str_replace('%', '', $item->fee_structure->sales_commission);
            $fee = ($commission / 100) * $price;

            array_push($lineItems, $this->saleCommission($fee, $item, $itemFrom, "OUTPUTY23"));

            if ($item->fee_structure->performance_commission_setting == 1) {
                if ($price > $item->high_estimate) {
                    $commission = (float) str_replace(['%', '+'], '', $item->fee_structure->performance_commission);
                    $fee = ($commission / 100) * $price;
                    array_push($lineItems, $this->performanceCommission($fee, $item, $itemFrom, "OUTPUTY23"));
                }
            }
        }

        if ($item->fee_structure->insurance_fee_setting == 1) {
            $commission = (float) str_replace('%', '', $item->fee_structure->insurance_fee);
            $fee = ($commission / 100) * $price;

            array_push($lineItems, $this->insuranceCommission($fee, $item, "OUTPUTY23"));
        }

        if ($item->fee_structure->listing_fee_setting == 1) {
            $fee = (float) str_replace('$', '', $item->fee_structure->listing_fee);
            array_push($lineItems, $this->listingFee($fee, $item, $itemFrom, $taxType));
        }


        return $lineItems;
    }

    public function getBuyerLineItems($price, $item, $buyer, $auction_id = null)
    {
        $taxType = TaxType::ZERORATEDOUTPUT;

        if ($buyer->buyer_gst_status == 1) {
            $taxType = "OUTPUTY23";
        }

        if ($auction_id != null) {
            $taxType = "OUTPUTY23";
        }

        $lineItems = [];
        $auction = Auction::findOrFail($auction_id);
        $buyerPremiun = $auction->buyers_premium;

        array_push($lineItems, $this->autionBuyerHammer($price, $item, $taxType));
        if($buyerPremiun > 0){
            array_push($lineItems, $this->buyerPremiun($price, $item, $taxType, $buyerPremiun));
        }

        return $lineItems;
    }

    protected function buyerPremiun($hammerPrice, $item, $taxType, $buyerPremiun = 25)
    {
        $xeroItem = XeroItem::where('item_code', 'Auct - Buyer Prem')->first();

        $business = $this->getTrackingById(7);
        if ($item->is_hotlotz_own_stock == 'N') {
            $business = $this->getTrackingById(2);
        }
        $category = $item->category->name;

        $lotNumber = AuctionItem::where('item_id', $item->id)->pluck('lot_number')->first();

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - Lot ' . $lotNumber . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount(($buyerPremiun / 100) * $hammerPrice)
            ->setAccountCode($xeroItem->sales_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function autionSellerHammer($hammerPrice, $item, $taxType)
    {
        $business = $this->getTrackingById(7);
        if ($item->is_hotlotz_own_stock == 'N') {
            $business = $this->getTrackingById(2);
            $xeroItem = XeroItem::where('item_code', 'Auct - Hammer')->first();
        } else {
            $xeroItem = XeroItem::where('item_code', 'OS Auct - Hammer')->first();
        }
        $category = $item->category->name;

        $lotNumber = AuctionItem::where('item_id', $item->id)->pluck('lot_number')->first();

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - Lot ' . $lotNumber . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount($hammerPrice)
            ->setAccountCode($xeroItem->purchases_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function autionBuyerHammer($hammerPrice, $item, $taxType)
    {
        $xeroItem = XeroItem::where('item_code', 'Auct - Hammer')->first();

        $business = $this->getTrackingById(7);
        $itemCode = $xeroItem->item_code;
        $saleAccount = $xeroItem->sales_account;

        if ($item->is_hotlotz_own_stock == 'Y') {
            $itemCode = $item->item_number;
            $saleAccount = 200;
        }

        if ($item->is_hotlotz_own_stock == 'N') {
            $business = $this->getTrackingById(2);
        }
        $category = $item->category->name;

        $lotNumber = AuctionItem::where('item_id', $item->id)->pluck('lot_number')->first();

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($itemCode)
            ->setDescription($xeroItem->sales_description . ' - Lot ' . $lotNumber . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount($hammerPrice)
            ->setAccountCode($saleAccount)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function fixedCostSale($fee, $item, $itemForm = 'auction', $taxType)
    {
        $xeroItem = XeroItem::where('item_code', 'Fixed Cost Sale Fee')->first();
        if ($itemForm == 'auction') {
            $business = $this->getTrackingById(7);
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(2);
            }
        } else {
            $business = $this->getTrackingById(8);
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(3);
            }
        }
        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount('-' . $fee)
            ->setAccountCode($xeroItem->sales_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function saleCommission($fee, $item, $itemForm = 'auction', $taxType)
    {
        $minimum_commission = (float) str_replace('$', '', $item->fee_structure->minimum_commission);

        if ($item->fee_structure->minimum_commission_setting == 1 && $fee <= $minimum_commission) {
            $code = 'Com - Minimum';
            $fee = $minimum_commission;
            $sales_commission = (float) $item->fee_structure->minimum_commission .'$';
        } else {
            $code = 'Commission';
            $sales_commission = (float) $item->fee_structure->sales_commission .'%';
        }

        $xeroItem = XeroItem::where('item_code', $code)->first();

        if ($itemForm == 'auction') {
            $business = $this->getTrackingById(7);
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(2);
            }
        } else {
            $business = $this->getTrackingById(8);

            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(3);
            }
        }

        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - ' . $sales_commission)
            ->setQuantity(1)
            ->setUnitAmount('-' . $fee)
            ->setAccountCode($xeroItem->sales_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function performanceCommission($fee, $item, $itemForm = 'auction', $taxType)
    {
        $xeroItem = XeroItem::where('item_code', 'Com - Perform')->first();

        if ($itemForm == 'auction') {
            $business = $this->getTrackingById(7);
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(2);
            }
        } else {
            $business = $this->getTrackingById(8);
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(3);
            }
        }

        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - ' . (float) $item->fee_structure->performance_commission . '%')
            ->setQuantity(1)
            ->setUnitAmount('-' . $fee)
            ->setAccountCode($xeroItem->sales_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function insuranceCommission($fee, $item, $taxType)
    {
        $xeroItem = XeroItem::where('item_code', 'Insurance')->first();

        $business = $this->getTrackingById(16);
        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - ' . (float) $item->fee_structure->insurance_fee . '%')
            ->setQuantity(1)
            ->setUnitAmount('-' . $fee)
            ->setAccountCode($xeroItem->sales_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function listingFee($fee, $item, $itemForm = 'auction', $taxType)
    {
        $xeroItem = XeroItem::where('item_code', 'Listing Fee')->first();
        if ($itemForm == 'auction') {
            $business = $this->getTrackingById(7);
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(2);
            }
        } else {
            $business = $this->getTrackingById(8);
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(3);
            }
        }
        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount('-' . $fee)
            ->setAccountCode($xeroItem->sales_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    public function setTracking($businessOption = null, $categoryOption = null)
    {
        $arr_tracking = [];
        $business_tracking = [
            'name' => 'Business',
            'option' => $businessOption
        ];

        array_push($arr_tracking, $business_tracking);

        $category_tracking = [
            'name' => 'Category',
            'option' => $categoryOption
        ];

        array_push($arr_tracking, $category_tracking);

        return $arr_tracking;
    }

    protected function marketplaceSellerAccountCode($price, $item, $taxType)
    {
        $xeroItem = XeroItem::where('item_code', 'MP - Fixed Price')->first();

        $business = $this->getTrackingById(8);

        if ($item->is_hotlotz_own_stock == 'N') {
            $business = $this->getTrackingById(3);
            $xeroItem = XeroItem::where('item_code', 'MP - Fixed Price')->first();
        } else {
            $xeroItem = XeroItem::where('item_code', 'OS MP - Fixed Price')->first();
        }

        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount($price)
            ->setAccountCode($xeroItem->purchases_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    public function createCustomerInvoice($customer_id, $invoice_id, $invoice_type, $type, $xero_invoice_data, $auction_id = null, $order_id = null)
    {
        if ($type == 'bill') {
            $invoice_url = null;
        } else {
            $invoice_url = $this->accountingAutomate->getInvoiceUrl($invoice_id);
        }

        return CustomerInvoice::firstOrCreate(
            [
                'customer_id' => $customer_id,
                'auction_id' => $auction_id,
                'invoice_id' => $invoice_id,
            ],
            [
                'customer_id' => $customer_id,
                'auction_id' => $auction_id,
                'invoice_id' => $invoice_id,
                'invoice_type' => $invoice_type,
                'type' => $type,
                'invoice_date' => date('Y-m-d H:i:s'),
                'invoice_url' => $invoice_url,
                'xero_invoice_data' => $xero_invoice_data,
                'order_summary_id' => $order_id,
                'active' => 0,
            ]
        );
    }

    public function createCustomerInvoiceItem($customer_invoice_id, $item_id, $price, $type = 'auction')
    {
        if ($type == 'marketplace') {
            return CustomerMarketplaceItem::firstOrCreate(
                [
                    'customer_invoice_id' => $customer_invoice_id,
                    'item_id' => $item_id,
                    'price' => $price
                ],
                [
                    'customer_invoice_id' => $customer_invoice_id,
                    'item_id' => $item_id,
                    'price' => $price
                ]
            );
        } elseif ($type == 'adhoc' || $type == 'withdraw') {
            return CustomerInvoiceItem::firstOrCreate(
                [
                    'customer_invoice_id' => $customer_invoice_id,
                    'xero_item_id' => $item_id,
                    'price' => $price
                ],
                [
                    'customer_invoice_id' => $customer_invoice_id,
                    'xero_item_id' => $item_id,
                    'price' => $price
                ]
            );
        } else {
            return CustomerInvoiceItem::firstOrCreate(
                [
                    'customer_invoice_id' => $customer_invoice_id,
                    'item_id' => $item_id,
                    'price' => $price
                ],
                [
                    'customer_invoice_id' => $customer_invoice_id,
                    'item_id' => $item_id,
                    'price' => $price
                ]
            );
        }
    }

    public function withdrawFee($fee, $item, $itemForm = 'marketplace', $taxType)
    {
        $xeroItem = XeroItem::where('item_code', 'Withdrawal Fee')->first();
        if ($itemForm == 'auction') {
            $business = $this->getTrackingById(7);
            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(2);
            }
        } else {
            $business = $this->getTrackingById(8);

            if ($item->is_hotlotz_own_stock == 'N') {
                $business = $this->getTrackingById(3);
            }
        }

        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
                ->setDescription($xeroItem->sales_description . ' - ' . $item->name)
                ->setQuantity(1)
                ->setUnitAmount($fee)
                ->setAccountCode($xeroItem->sales_account)
                ->setTaxType($taxType)
                ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function privateBuyerPremiun($price, $item, $taxType, $buyerPremiun = 25)
    {
        $business = 'Priv - Buyer Prem';
        if ($item->is_hotlotz_own_stock == 'Y') {
            $business = 'OS Priv - Buyer Prem';
        }
        $xeroItem = XeroItem::where('item_code', $business)->first();

        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount(($buyerPremiun / 100) * $price)
            ->setAccountCode($xeroItem->sales_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function privateBuyerPrice($price, $item, $taxType, $itemForm = 'auction')
    {
        $xeroItem = XeroItem::where('item_code', 'Private - Hammer')->first();

        if ($itemForm == 'auction') {
            $business = $this->getTrackingById(9);
        } else {
            $business = $this->getTrackingById(10);
        }

        $itemCode = $xeroItem->item_code;
        $saleAccount = $xeroItem->sales_account;

        if ($item->is_hotlotz_own_stock == 'Y') {
            $itemCode = $item->item_number;
            $saleAccount = 200;
        }

        if ($item->is_hotlotz_own_stock == 'N') {
            $business = $this->getTrackingById(4);
        }
        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($itemCode)
            ->setDescription($xeroItem->sales_description . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount($price)
            ->setAccountCode($saleAccount)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    public function getPrivateBuyerLineItems($price, $item, $buyer, $buyerPremiun = 25, $itemForm = 'auction')
    {
        if ($buyer->buyer_gst_status == 1) {
            $taxType = "OUTPUTY23";
            $price = $price;
        } else {
            $taxType = TaxType::ZERORATEDOUTPUT;
            $price = number_format($price / 1.08, 4, '.', '');
        }

        $lineItems = [];

        array_push($lineItems, $this->privateBuyerPrice($price, $item, $taxType, $itemForm));
        if ($buyerPremiun > 0) {
            array_push($lineItems, $this->privateBuyerPremiun($price, $item, $taxType, $buyerPremiun));
        }

        return $lineItems;
    }

    protected function privateSellerPrice($price, $item, $taxType, $itemForm = 'auction')
    {
        $xeroItem = XeroItem::where('item_code', 'Private - Hammer')->first();

        if ($itemForm == 'auction') {
            $business = $this->getTrackingById(9);
        } else {
            $business = $this->getTrackingById(10);
        }

        if ($item->is_hotlotz_own_stock == 'N') {
            $business = $this->getTrackingById(4);
        }

        $category = $item->category->name;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($xeroItem->item_code)
            ->setDescription($xeroItem->sales_description . ' - ' . $item->name)
            ->setQuantity(1)
            ->setUnitAmount($price)
            ->setAccountCode($xeroItem->purchases_account)
            ->setTaxType($taxType)
            ->setTracking($this->setTracking($business, $category));

        return $lineItem;
    }

    protected function getTrackingById($id)
    {
        return XeroTracking::where('id', $id)->first()->name;
    }

    public function createCreditNoteAuthorised($contactId, $lineItems, $taxType, $type)
    {
        $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
        $contact->setContactId($contactId);

        $creditnote = new \XeroAPI\XeroPHP\Models\Accounting\CreditNote;

        $creditnote->setDate(Carbon::now())
            ->setContact($contact)
            ->setLineItems($lineItems)
            ->setStatus(\XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_AUTHORISED)
            ->setLineAmountTypes($taxType)
            ->setType($type);

        return $creditnote;
    }
}
