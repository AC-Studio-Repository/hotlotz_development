<?php

namespace App\Modules\Testimonial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;
use App\Modules\HomePage\Models\HomePageTestimonial;

class Testimonial extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public $table = 'testimonial';

    public function getQuote()
    {
        return $this->quote;
    }

    public function homePageTestimonial()
    {
        return $this->hasMany(HomePageTestimonial::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($testimonial) {
            $relationMethods = ['homePageTestimonial'];

            foreach ($relationMethods as $relationMethod) {
                if ($testimonial->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }
}
