<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrivateSaleFieldsIntoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dateTime('cancel_sale_date')->nullable();
            $table->string('private_sale_type')->nullable(); //[auction,privatesale]
            $table->uuid('private_sale_auction_id')->nullable();
            $table->decimal('private_sale_price',15,2)->nullable();
            $table->integer('private_sale_buyer_premium')->nullable();
            $table->dateTime('private_sale_date')->nullable();
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
