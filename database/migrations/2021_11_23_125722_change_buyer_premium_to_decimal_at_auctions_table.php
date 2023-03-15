<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBuyerPremiumToDecimalAtAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `auctions` CHANGE `vat_rate` `vat_rate` DECIMAL(15,2) NOT NULL DEFAULT '0.07'");
            DB::statement("ALTER TABLE `auctions` CHANGE `buyers_premium` `buyers_premium` DECIMAL(15,2) NOT NULL DEFAULT '0'");
            DB::statement("ALTER TABLE `auctions` CHANGE `internet_surcharge_rate` `internet_surcharge_rate` DECIMAL(15,2) NOT NULL DEFAULT '0'");
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
