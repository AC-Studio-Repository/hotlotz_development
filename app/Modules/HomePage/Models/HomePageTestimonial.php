<?php

namespace App\Modules\HomePage\Models;

use Illuminate\Database\Eloquent\Model;
use Hash;
use App\Modules\Testimonial\Models\Testimonial;

class HomePageTestimonial extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    public $table = 'homepage_testimonial';

    public function testimonial()
    {
        return $this->belongsTo(Testimonial::class, 'testimonial_id');
    }
}
