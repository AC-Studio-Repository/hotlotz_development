<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewInfoColumnsToStrategicPartnersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('strategic_partners_info', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('blog');
            $table->string('caption')->nullable()->after('banner_image');
            $table->string('blog_header_1')->nullable()->after('caption');
            $table->text('blog_1')->nullable()->after('blog_header_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strategic_partners_info', function (Blueprint $table) {
            //
        });
    }
}
