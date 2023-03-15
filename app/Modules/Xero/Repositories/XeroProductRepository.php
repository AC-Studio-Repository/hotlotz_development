<?php

namespace App\Modules\Xero\Repositories;

use Illuminate\Support\Carbon;
use App\Modules\Item\Models\Item;
use XeroAPI\XeroPHP\Models\Accounting\Items;
use XeroAPI\XeroPHP\Models\Accounting\TaxType;
use App\Modules\Xero\Repositories\XeroContactRepository;
use App\Modules\Xero\Repositories\XeroControlRepository;

class XeroProductRepository
{
    public function __construct(
        XeroContactRepository $xeroContactRepository,
        XeroControlRepository $xeroControlRepository
    ) {
        $this->xeroContactRepository = $xeroContactRepository;
        $this->xeroControlRepository = $xeroControlRepository;
    }

    public function createProduct($payload, $xeroTenantId, $apiInstance, $returnObj=false)
    {
        $str = '';

        //[Items:Create]
        $arr_items = [];

        $dbItem = Item::findOrFail($payload);

        $purchase = new \XeroAPI\XeroPHP\Models\Accounting\Purchase;
        $purchase->setUnitPrice($dbItem->purchase_cost ?? 0)
            ->setTaxType(TaxType::ZERORATEDINPUT)
            ->setCOGSAccountCode(300);

        $item = new \XeroAPI\XeroPHP\Models\Accounting\Item;
        $item->setName($dbItem->name)
            ->setCode($dbItem->item_number)
            ->setDescription($dbItem->long_description)
            ->setPurchaseDescription($dbItem->long_description)
            ->setIsTrackedAsInventory(true)
            ->setInventoryAssetAccountCode(630)
            ->setPurchaseDetails($purchase)
            ->setTotalCostPool($dbItem->purchase_cost)
            ->setQuantityOnHand(1);

        array_push($arr_items, $item);

        $items = new Items;
        $items->setItems($arr_items);

        $result = $apiInstance->createItems($xeroTenantId, $items, true);

        $dbItem->xero_item_id = $result->getItems()[0]->getItemId();
        $dbItem->save();

        $this->createOpeningInventoryBill($dbItem, $xeroTenantId, $apiInstance);
        //[/Items:Create]

        $str = $str . "Create item: " . $result->getItems()[0]->getName();

        if ($returnObj) {
            return $result;
        } else {
            return $str;
        }
    }

    public function updateProduct($payload, $xeroTenantId, $apiInstance)
    {
        $str = '';

        //[Items:Update]
        $item = new Item;

        $dbItem = Item::findOrFail($payload);

        $purchase = new \XeroAPI\XeroPHP\Models\Accounting\Purchase;
        $purchase->setUnitPrice($dbItem->purchase_cost ?? 0)
            ->setTaxType(TaxType::ZERORATEDINPUT)
            ->setCOGSAccountCode(300);

        $item = new \XeroAPI\XeroPHP\Models\Accounting\Item;
        $item->setName($dbItem->name)
            ->setCode($dbItem->item_number)
            ->setDescription($dbItem->long_description)
            ->setPurchaseDescription($dbItem->long_description)
            ->setIsTrackedAsInventory(true)
            ->setInventoryAssetAccountCode(630)
            ->setPurchaseDetails($purchase)
            ->setQuantityOnHand(1);
        $result = $apiInstance->updateItem($xeroTenantId, $dbItem->xero_product_id, $item);

        $dbItem->xero_item_id = $result->getItems()[0]->getItemId();
        $dbItem->save();

        $str = $str . "Update item: " . $result->getItems()[0]->getName();

        return $str;
    }

    public function updateSalePrice($payload, $xeroTenantId, $apiInstance)
    {
        $str = '';

        $sales = new \XeroAPI\XeroPHP\Models\Accounting\Purchase;
        $sales->setUnitPrice($payload['price'])
            ->setTaxType($payload['tax'])
            ->setAccountCode(200);

        //[Items:Update]
        $item = new \XeroAPI\XeroPHP\Models\Accounting\Item;
        $item->setCode($payload['item_code'])
            ->setSalesDetails($sales);

        $result = $apiInstance->updateItem($xeroTenantId, $payload['xero_product_id'], $item);

        //[/Items:Update]

        $str = $str . "Update Sale Price item: " . $result->getItems()[0]->getName();

        return $str;
    }

    public function createOpeningInventoryBill($item, $xeroTenantId, $apiInstance)
    {
        $sellerContactID = $this->xeroContactRepository->createOrGetContact($item->customer_id);

        $sellerContact = $this->xeroControlRepository->setXeroContact($sellerContactID);

        $sellerLineItems = [];

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setItemCode($item->item_number)
            ->setDescription($item->name)
            ->setQuantity(1)
            ->setAccountCode(630)
            ->setUnitAmount($item->purchase_cost);

        $sellerLineItems[] = $lineItem;

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
        $lineItem->setDescription('Inventory Opening Balance')
            ->setQuantity(1)
            ->setUnitAmount('-'.$item->purchase_cost)
            ->setAccountCode(333);

        $sellerLineItems[] = $lineItem;

        $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;

        $invoice->setDueDate(Carbon::now())
            ->setContact($sellerContact)
            ->setLineItems($sellerLineItems)
            ->setStatus('AUTHORISED')
            ->setLineItems($sellerLineItems)
            ->setType('ACCPAY')
            ->setLineAmountTypes('NoTax');

        $apiInstance->createInvoices($xeroTenantId, $invoice, true);
    }
}
