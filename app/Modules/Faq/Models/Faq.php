<?php

namespace App\Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use App\Modules\FaqCategory\Models\FaqCategory;

class Faq extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'faq';

    public function faqcategory()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}
