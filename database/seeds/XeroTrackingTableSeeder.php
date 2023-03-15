<?php

use Illuminate\Database\Seeder;
use App\Modules\Xero\Models\XeroTracking;

class XeroTrackingTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('xero_trackings')->truncate();

        $data = json_decode(file_get_contents(database_path().'/seeds/data/xeroTracking.json'), true);
        foreach ($data['TrackingCategories'] as $obj) {
            foreach ($obj['Options'] as $option) {
                if ($option['IsActive'] == true) {
                    XeroTracking::create(array(
                        'type' => $obj['Name'],
                        'name' => $option['Name'],
                        'xero_tracking_category_id' => $obj['TrackingCategoryID'],
                        'xero_tracking_option_id' => $option['TrackingOptionID']
                    ));
                }
            }
        }
    }
}
