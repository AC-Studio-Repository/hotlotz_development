<?php

namespace App\Repositories;

use DB;

use App\Modules\Category\Models\Category;

class CategoryRepository
{
    public function __construct()
    {
    }

    public function getAllCategories()
    {
        $category = Category::whereNull('parent_id')->orderBy('name', 'ASC')->where('name', '!=', 'Collaborations')->get();

        return $category->pluck('id', 'name');
    }
}
