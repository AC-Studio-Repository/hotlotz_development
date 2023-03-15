<?php

use Illuminate\Database\Seeder;
use App\Modules\Xero\Models\XeroItem;

class XeroItemTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('xero_items')->truncate();

        $data = json_decode(file_get_contents(database_path().'/seeds/data/xeroItems.json'), true);
        foreach ($data['Items'] as $obj) {
            XeroItem::create(array(
                'item_code' => $obj['Code'],
                'item_name' => $obj['Name'],
                'purchases_description' => isset($obj['PurchaseDescription']) ? $obj['PurchaseDescription'] : null,
                'purchases_account' => isset($obj['PurchaseDetails']['AccountCode']) ? $obj['PurchaseDetails'] ['AccountCode']: 0,
                'sales_description' => isset($obj['Description']) ? $obj['Description'] : null,
                'sales_account' => isset($obj['SalesDetails']['AccountCode']) ? $obj['SalesDetails']['AccountCode'] : 0,
                'xero_product_id' => $obj['ItemID'],
                'sale_tax_rate' => $obj['SalesTaxRate']
            ));
        }
    }
}
