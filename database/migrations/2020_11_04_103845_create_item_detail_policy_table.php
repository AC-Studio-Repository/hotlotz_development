<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemDetailPolicyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_detail_policy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('collection_Shipping_header')->nullable();
            $table->text('collection_Shipping_blog')->nullable();
            $table->string('one_tree_planted_header')->nullable();
            $table->text('one_tree_planted_blog')->nullable();
            $table->string('sale_policy_header')->nullable();
            $table->text('sale_policy_blog')->nullable();
            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
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
        Schema::dropIfExists('item_detail_policy');
    }
}
