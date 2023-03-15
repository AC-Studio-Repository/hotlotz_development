<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComingAuctionColumnsToAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->string('banner_full_path')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
            $table->string('banner_file_path')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
            $table->string('banner_file_name')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
            $table->text('consignment_info')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
            $table->datetime('consignment_deadline')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
            $table->text('auction_detail')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
            $table->datetime('viewing_date_end')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
            $table->datetime('viewing_date_start')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
            $table->string('coming_auction_url')->nullable()->after('advanced_time_bidding_enabled')->comment('Coming Auction Field');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            //
        });
    }
}
