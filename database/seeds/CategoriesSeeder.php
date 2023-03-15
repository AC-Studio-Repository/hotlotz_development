<?php

use Illuminate\Database\Seeder;
use App\Helpers\NHelpers;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Empty the categories table
        DB::table('categories')->truncate();
        DB::table('category_properties')->truncate();

        //Get all of the categories
        $categories = json_decode(file_get_contents(database_path().'/seeds/new_categories.json'), true);
        
        foreach ($categories as $key => $category) {
            //Insert into categories table
            DB::table('categories')->insert([
                'id' => $category['id'],
                'parent_id' => $category['parent_id'],
                'name' => $category['name']
            ] + NHelpers::created_updated_at_by());

            //If exists Properties, insert into category_properties table
            if(isset($category['properties']) && count($category['properties'])>0){

                foreach ($category['properties'] as $key => $property) {
                    DB::table('category_properties')->insert([
                        'id' => $property['id'],
                        'category_id' => $property['category_id'],
                        'key' => $property['key'],
                        'value' => $property['value'],
                        'field_type' => $property['field_type'],
                        'is_required' => $property['is_required'],
                        'is_filter' => $property['is_filter'],
                    ] + NHelpers::created_updated_at_by());
                }

            }
        }
    }
}
