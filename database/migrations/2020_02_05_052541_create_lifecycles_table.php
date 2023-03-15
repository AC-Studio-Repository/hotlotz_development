<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifecyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lifecycles', function (Blueprint $table) {
            // $table->uuid('id')->primary();
            $table->increments('id');
            $table->string('name');
            $table->string('level1', 50);
            $table->string('level2', 50);
            $table->string('level3', 50);
            $table->string('level4', 50);
            $table->string('level5', 50);
            $table->text('description');
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
        Schema::dropIfExists('lifecycles');
    }
}
