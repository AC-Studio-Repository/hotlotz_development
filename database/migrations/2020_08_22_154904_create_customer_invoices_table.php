<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->uuid('invoice_id')->nullable();
            $table->uuid('auction_id')->nullable();
            $table->enum('invoice_type', ['auction', 'marketplace', 'adhoc'])->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->longText('xero_invoice_data')->nullable();
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
        Schema::dropIfExists('customer_invoices');
    }
}
