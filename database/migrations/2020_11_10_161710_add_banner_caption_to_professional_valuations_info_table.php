<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerCaptionToProfessionalValuationsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professional_valuations_info', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('id');
            $table->string('caption')->nullable()->after('banner_image');
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
