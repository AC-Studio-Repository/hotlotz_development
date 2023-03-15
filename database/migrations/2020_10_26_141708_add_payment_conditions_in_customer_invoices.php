<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentConditionsInCustomerInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            $table->boolean('payment_processing')->default(0);
            $table->enum('payment_type', ['stripe', 'bank'])->default('stripe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
        });
    }
}
