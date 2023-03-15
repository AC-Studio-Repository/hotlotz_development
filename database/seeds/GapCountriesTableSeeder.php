<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Models\GapCountry;
use App\Helpers\NHelpers;

class GapCountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\ReferenceDataApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getCounteries();
            \Log::info('Get GAP Countries -> SUCCESS');

        } catch (\Exception $e) {
            \Log::error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }

        DB::table('gap_countries')->truncate();

        $rows = [];
        if(count($result) > 0){
	        foreach ($result as $key => $value) {
	        	$rows[] = [
	                'code' => isset($value['code'])?$value['code']:'',
	                'name' => isset($value['name'])?$value['name']:'',
	                'dialling_prefix' => isset($value['dialling_prefix'])?$value['dialling_prefix']:'',
	            ];
	        }
        }

        if (count($rows) > 0) {
            Seed::insertData(GapCountry::class, $rows);
        }
    }
}
