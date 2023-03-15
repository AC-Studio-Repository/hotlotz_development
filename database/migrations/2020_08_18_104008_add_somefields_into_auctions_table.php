<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomefieldsIntoAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->longText('sr_reference')->nullable()->after('sr_auction_id');
            $table->longText('sr_auction_data')->nullable()->after('sr_reference');
            $table->longText('bidders_list')->nullable()->after('sr_auction_data');
            $table->longText('winners_list')->nullable()->after('bidders_list');
            $table->longText('sr_sale_result')->nullable()->after('winners_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
