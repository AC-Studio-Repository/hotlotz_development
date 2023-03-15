<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePayloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_payloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->uuid('invoice_id')->nullable();
            $table->string('event');
            $table->longText('payload');
            $table->boolean('pass')->default('0');
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
        Schema::dropIfExists('stripe_payloads');
    }
}