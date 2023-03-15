<?php

use Illuminate\Database\Seeder;

class AuctionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	## Customer
    	$customer_path = base_path() . '/database/seeds/auction_test_data/customers.sql';
        \Log::info('customer_path : '.$customer_path);
		$customer_sql = file_get_contents($customer_path);
		DB::unprepared($customer_sql);


		## Auction
    	$auction_path = base_path() . '/database/seeds/auction_test_data/auctions.sql';
        \Log::info('auction_path : '.$auction_path);
		$auction_sql = file_get_contents($auction_path);
		DB::unprepared($auction_sql);


		## Item
    	$item_path = base_path() . '/database/seeds/auction_test_data/items.sql';
        \Log::info('item_path : '.$item_path);
		$item_sql = file_get_contents($item_path);
		DB::unprepared($item_sql);


		## ItemLifecycle
    	$item_lifecycle_path = base_path() . '/database/seeds/auction_test_data/item_lifecycles.sql';
        \Log::info('item_lifecycle_path : '.$item_lifecycle_path);
		$item_lifecycle_sql = file_get_contents($item_lifecycle_path);
		DB::unprepared($item_lifecycle_sql);


		## AuctionItem
    	$auction_item_path = base_path() . '/database/seeds/auction_test_data/auction_items.sql';
        \Log::info('auction_item_path : '.$auction_item_path);
		$auction_item_sql = file_get_contents($auction_item_path);
		DB::unprepared($auction_item_sql);
    }
}
