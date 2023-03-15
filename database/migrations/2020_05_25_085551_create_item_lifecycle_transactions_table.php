<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemLifecycleTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_lifecycle_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('item_id');
            $table->uuid('auction_id')->nullable();
            $table->string('status')->comment('Auction, Marketplace, Clearance, Storage, Private Sale, Sold');
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
        Schema::dropIfExists('item_lifecycle_transactions');
    }
}
