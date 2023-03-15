<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlog3ToHomeContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_content', function (Blueprint $table) {
            $table->string('blog_header_3')->nullable()->after('blog_2');
            $table->text('blog_3')->nullable()->after('blog_header_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_content', function (Blueprint $table) {
            //
        });
    }
}
