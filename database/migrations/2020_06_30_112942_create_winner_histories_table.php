<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinnerHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winner_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('bidder_id');
            $table->uuid('customer_id');
            $table->string('reference');
            $table->string('paddle_number');
            $table->boolean('deposit_held')->default(0);
            $table->integer('number_of_lots_won');
            $table->integer('total_hammer');
            $table->boolean('vat_exempt')->default(0);
            $table->boolean('created_via_api')->default(0);

            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('winner_histories');
    }
}
