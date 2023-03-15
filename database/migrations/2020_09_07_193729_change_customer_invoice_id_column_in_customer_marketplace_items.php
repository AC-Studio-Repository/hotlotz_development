<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCustomerInvoiceIdColumnInCustomerMarketplaceItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_marketplace_items');

        Schema::create('customer_marketplace_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('customer_invoice_id')->nullable();
            $table->uuid('item_id')->nullable();
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
        Schema::table('customer_marketplace_items', function (Blueprint $table) {
            //
        });
    }
}
