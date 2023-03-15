<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuctionResultRelatedColumnsInAuctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->string('total_lots', 20)->nullable()->comment('Auction Result Field');
            $table->string('total_bids', 20)->nullable()->comment('Auction Result Field');
            $table->string('high_estimate', 20)->nullable()->comment('Auction Result Field');
            $table->string('low_estimate', 20)->nullable()->comment('Auction Result Field');
            $table->string('hammer_total', 20)->nullable()->comment('Auction Result Field');
            $table->string('lots_sold', 20)->nullable()->comment('Auction Result Field');
            $table->string('percentage_sold', 20)->nullable()->comment('Auction Result Field');
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
