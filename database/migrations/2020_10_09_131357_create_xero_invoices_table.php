<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXeroInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xero_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['auction', 'marketplace', 'adhoc']);
            $table->integer('buyer_id');
            $table->integer('seller_id')->nullable();
            $table->uuid('item_id');
            $table->uuid('auction_id')->nullable();
            $table->float('price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xero_invoices');
    }
}
