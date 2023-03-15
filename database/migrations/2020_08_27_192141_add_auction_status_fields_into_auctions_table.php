<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuctionStatusFieldsIntoAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->boolean('is_closed')->default(0)->after('is_published');
            $table->boolean('is_submitted')->default(0)->after('is_closed');
            $table->boolean('is_ready_invoice')->default(0)->after('is_submitted');
            $table->boolean('is_invoiced')->default(0)->after('is_ready_invoice');
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
