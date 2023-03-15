        <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if(Schema::hasColumns('countries',['name','phonecode','is_eu_member'])){
            Schema::table('countries',function($table){
                // $table->dropColumn(['name','phonecode','is_eu_member']);
                // $table->dropColumn(['name']);
                $table->string('id',10)->change();
                $table->string('name')->default('')->change();
                $table->integer('phonecode')->nullable()->change();
                $table->boolean('is_eu_member')->default(1)->nullable()->change();
            });
        }

        Schema::table('provinces',function($table){
            $table->string('country_id',10)->change();
        });

        Schema::table('addresses',function($table){
            $table->string('country_id',10)->change();
        });
        
        Schema::table('countries', function ($table) {
            $table->string('capital', 255)->nullable();
            $table->string('citizenship', 255)->nullable();
            $table->string('country_code', 3)->default('');
            $table->string('currency', 255)->nullable();
            $table->string('currency_code', 255)->nullable();
            $table->string('currency_sub_unit', 255)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('iso_3166_2', 2)->default('');
            $table->string('iso_3166_3', 3)->default('');
            // $table->string('name', 255)->default('');
            $table->string('region_code', 3)->default('');
            $table->string('sub_region_code', 3)->default('');
            $table->boolean('eea')->default(0);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('countries');
    }
}
