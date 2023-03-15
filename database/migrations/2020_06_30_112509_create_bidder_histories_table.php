<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bidder_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('auction_id');
            $table->uuid('lot_id');
            $table->string('lot_number');
            $table->decimal('bid_amount', 15, 2);
            $table->datetime('bid_placed_date');
            $table->uuid('bidder_id');
            $table->uuid('customer_id');
            $table->string('bid_type');

            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bidder_histories');
    }
}
