<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyConatactToProfessionalValuationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professional_valuations_info', function (Blueprint $table) {
            $table->integer('key_contact_1')->unsigned()->default(0)->after('blog');
            $table->integer('key_contact_2')->unsigned()->default(0)->after('key_contact_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professional_valuations_info', function (Blueprint $table) {
            //
        });
    }
}
