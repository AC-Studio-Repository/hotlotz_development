<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentTypeInCustomerInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            DB::statement("ALTER TABLE customer_invoices MODIFY COLUMN payment_type ENUM('stripe', 'bank', 'paynow')");
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
            //
        });
    }
}
