<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrivateInvoiceTypeInCustomerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            DB::statement("ALTER TABLE customer_invoices MODIFY COLUMN invoice_type ENUM('auction', 'marketplace', 'adhoc', 'withdraw', 'private')");
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
