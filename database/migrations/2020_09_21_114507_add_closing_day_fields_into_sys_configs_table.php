<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClosingDayFieldsIntoSysConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sys_configs', function (Blueprint $table) {
            $table->string('is_closed_monday')->default('N')->nullable()->after('monday_end_time');
            $table->string('is_closed_tuesday')->default('N')->nullable()->after('tuesday_end_time');
            $table->string('is_closed_wednesday')->default('N')->nullable()->after('wednesday_end_time');
            $table->string('is_closed_thursday')->default('N')->nullable()->after('thursday_end_time');
            $table->string('is_closed_friday')->default('N')->nullable()->after('friday_end_time');
            $table->string('is_closed_saturday')->default('Y')->nullable()->after('saturday_end_time');
            $table->string('is_closed_sunday')->default('Y')->nullable()->after('sunday_end_time');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
