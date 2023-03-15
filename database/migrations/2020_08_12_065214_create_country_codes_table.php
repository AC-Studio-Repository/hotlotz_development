<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('country_code');
            $table->string('country_name');
            $table->string('dialling_code');
            $table->tinyInteger('order_by_status')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_codes');
    }
}
