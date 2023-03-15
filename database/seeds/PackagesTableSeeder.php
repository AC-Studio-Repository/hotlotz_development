<?php

use Illuminate\Database\Seeder;
use App\Helpers\Seed;
use App\Models\Package;
use Illuminate\Support\Str;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->truncate();

        $rows = [
            [
                'id' => 1,
                'name' => 'Auctioneer’s Discretion',
                'seller_commission' => '20%+',
                'reserve' => 'Auctioneer’s Discretion OR  Fixed Reserve',
                'min_commission' => '$40+',
                'listing_fee' => 'No',
                'channel' => 'Auction + MP',
                'withdraw_fee' => '$40+',
                'storage_fee' => '14d+ free then $5pd+',
                'description' => 'Entry level. Item/s consigned with no reserve. House has complete flexibility to set the pricing. Allows us to promote a ‘No Fee’s’ marketing message. SC is the same if item is sold in auction or MP',
            ],
            [
                'id' => 2,
                'name' => 'Seller’s Own Reserve',
                'seller_commission' => '20%+',
                'reserve' => 'Yes',
                'min_commission' => 'No',
                'listing_fee' => '$40+',
                'channel' => 'Auction',
                'withdraw_fee' => '$40+',
                'storage_fee' => '14d+ free then $5pd+',
                'description' => 'Item is offered for sale at a reserve higher than our recommendation. We charge a listing fee of $40, which covers our base costs. SC is the same if item is sold in auction or MP.',
            ],
            [
                'id' => 3,
                'name' => 'Marketplace Only',
                'seller_commission' => '30%+',
                'reserve' => 'Yes',
                'min_commission' => '$40+',
                'listing_fee' => 'No',
                'channel' => 'MarketPlace',
                'withdraw_fee' => '$40+',
                'storage_fee' => '14d+ free then $5pd+',
                'description' => '',
            ],
            [
                'id' => 4,
                'name' => 'Private Sale',
                'seller_commission' => '20%+',
                'reserve' => 'N/A',
                'min_commission' => 'N/A',
                'listing_fee' => 'No',
                'channel' => 'N/A',
                'withdraw_fee' => 'N/A',
                'storage_fee' => '14d+ free then $5pd+',
                'description' => '',
            ],
        ];

        if (count($rows) > 0) {
            Seed::insertData(Package::class, $rows);
        }
    }
}