<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThirdPartyPaymentAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_party_payment_alerts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->uuid('invoice_id');
            $table->string('invoice_number');
            $table->float('amount');
            $table->string('payment_method');
            $table->longText('payment_data')->nullable();
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
        Schema::dropIfExists('third_party_payment_alerts');
    }
}
