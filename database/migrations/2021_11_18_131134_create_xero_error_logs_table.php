<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXeroErrorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xero_error_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique_key')->nullable();
            $table->integer('seller_id')->nullable();
            $table->integer('buyer_id')->nullable();
            $table->uuid('item_id')->nullable();
            $table->uuid('invoice_id')->nullable();
            $table->float('amount')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('xero_error_logs');
    }
}
