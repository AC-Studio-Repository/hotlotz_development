<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('item_id');
            $table->uuid('auction_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('buyer_id')->nullable();
            $table->integer('item_lifecycle_id')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('sold_price', 15, 2)->nullable();
            $table->decimal('hammer_price', 15, 2)->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->decimal('left_to_pay', 15, 2)->nullable();
            $table->string('type')->nullable()->comment('lifecycle, auction, marketplace, withdraw, decline, dispatch, cancelsale, privatesale');
            $table->string('status')->nullable()->comment('Auction, Marketplace, Clearance, Storage, Sold, Unsold, Paid, Settled, Cancel Sale, Private Sale, Withdrawn, Declined, Dispatched');
            $table->string('email_flag')->nullable()->comment('Completed');
            $table->dateTime('entered_date')->nullable();
            $table->dateTime('finished_date')->nullable();
            $table->dateTime('specific_date')->nullable();

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
        Schema::dropIfExists('item_histories');
    }
}
