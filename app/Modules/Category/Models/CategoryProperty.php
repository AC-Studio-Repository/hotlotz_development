<?php

namespace App\Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use App\Modules\Category\Models\Category;


class CategoryProperty extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $table = 'category_properties';


    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    protected function getFieldType()
    {
        return [
            "text" => "Text Field",
            "radio" => "Radio Button",
            "checkbox" => "Checkbox",
            "dropdown" => "Dropdown",
            "dropdown&checkbox" => "Dropdown + checkbox",
        ];
    }
}
