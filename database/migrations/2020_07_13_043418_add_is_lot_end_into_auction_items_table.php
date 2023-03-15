<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLotEndIntoAuctionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_items', function (Blueprint $table) {
            $table->boolean('is_lot_ended')->default(0)->nullable()->after('sr_status');
            $table->dateTime('end_time_utc')->nullable()->after('is_lot_ended');
            $table->dateTime('sold_date')->nullable()->after('end_time_utc');
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
