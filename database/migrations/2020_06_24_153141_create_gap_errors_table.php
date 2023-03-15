<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGapErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gap_errors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('module')->nullable();
            $table->uuid('reference_id')->nullable();
            $table->string('action')->nullable();
            $table->string('error_name')->nullable();
            $table->longText('error')->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('gap_errors');
    }
}
