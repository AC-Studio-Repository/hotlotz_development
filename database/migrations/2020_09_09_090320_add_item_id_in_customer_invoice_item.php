<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemIdInCustomerInvoiceItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_invoice_items');

        Schema::create('customer_invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_invoice_id');
            $table->uuid('item_id')->nullable();
            $table->integer('xero_item_id')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->longText('notes')->nullable();
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
        Schema::table('customer_invoice_item', function (Blueprint $table) {
            //
        });
    }
}
