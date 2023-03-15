<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToAuctionsComingAuctionField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->text('coming_auction_about')->nullable()->after('consignment_deadline')->comment('Coming Auction Field');
            $table->boolean('coming_auction_tick')->default(0)->after('coming_auction_about')->comment('Coming Auction Field');
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
