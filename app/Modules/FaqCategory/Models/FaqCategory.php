<?php

namespace App\Modules\FaqCategory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use App\Modules\Faq\Models\Faq;

class FaqCategory extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public $table = 'faq_categories';

    public function faq()
    {
        return $this->hasMany(Faq::class);
    }

    public function getName()
    {
        return $this->name;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($faqcategory) {
            $relationMethods = ['faq'];

            foreach ($relationMethods as $relationMethod) {
                if ($faqcategory->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }
}
