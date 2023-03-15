<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_configs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('monday_start_time')->nullable();
            $table->string('monday_end_time')->nullable();

            $table->string('tuesday_start_time')->nullable();
            $table->string('tuesday_end_time')->nullable();

            $table->string('wednesday_start_time')->nullable();
            $table->string('wednesday_end_time')->nullable();

            $table->string('thursday_start_time')->nullable();
            $table->string('thursday_end_time')->nullable();

            $table->string('friday_start_time')->nullable();
            $table->string('friday_end_time')->nullable();

            $table->string('saturday_start_time')->nullable();
            $table->string('saturday_end_time')->nullable();

            $table->string('sunday_start_time')->nullable();
            $table->string('sunday_end_time')->nullable();

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
        Schema::dropIfExists('sys_configs');
    }
}
