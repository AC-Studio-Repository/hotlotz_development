<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSummarysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_summaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_no');
            $table->uuid('invoice_id')->nullable();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('address_id')->nullable();
            $table->float('total');
            $table->string('status')->default('pending');
            $table->enum('from', ['marketplace', 'auction'])->default('marketplace');
            $table->enum('type', ['ship', 'pickup'])->default('ship');
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
        Schema::dropIfExists('order_summaries');
    }
}
