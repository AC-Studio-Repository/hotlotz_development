<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailFlagFieldsIntoAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function(Blueprint $table) {
            $table->boolean('pre_sale_advice_email_flag')->default(0)->after('title')->comment('to be sent out email after add Item to Auction as Lot / before Auction begins');
            $table->boolean('post_sale_advice_email_flag')->default(0)->after('title')->comment('to be sent out email after Auction colse');
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
