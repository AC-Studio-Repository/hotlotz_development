<?php

namespace App\Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use App\Modules\Category\Models\CategoryProperty;
use App\Events\CategoryCreatedEvent;
use App\Events\CategoryUpdatedEvent;


class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => CategoryCreatedEvent::class,
    ];

    public function categoryproperties()
    {
        return $this->hasMany(CategoryProperty::class);
    }

    protected function getName()
    {
        return $this->name;
    }

    protected function getNameById($id)
    {
        $category = Category::find($id);
        return $category->name;
    }

    protected function getCategoryNameBySlug($slug)
    {
        $id = $this->getCategoryIdSlug($slug);
        $name = $this->getNameById($id);

        return $name;
    }

    protected function getCategoryIdSlug($slug)
    {
        switch ($slug) {
            case 'maps-bonds':
                return 1;
                break;
            case 'designer-fashion':
                return 4;
                break;
            case 'home-decor':
                return 7;
                break;
            case 'tableware':
                return 9;
                break;
            case 'asian-collectables':
                return 3;
                break;
             case 'art':
                return 2;
                break;
            case 'jewellery':
                return 6;
                break;
            case 'watches':
                return 10;
                break;
            case 'decorative-arts':
                return 12;
                break;
            case 'furniture':
                return 5;
                break;
            case 'rugs-carpets':
                return 8;
                break;
            case 'wine-spirits':
                return 11;
                break;
            case 'collaborations':
                return 13;
                break;
            default:
                return '';
                break;
        }
    }
}
