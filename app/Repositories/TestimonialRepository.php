<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

use App\Modules\HomePage\Models\HomePageTestimonial;

class TestimonialRepository
{

    public function __construct(){

    }

    public function getTestimonials(){

        $homepageTestimonials = HomePageTestimonial::with('Testimonial')->get();
        $testimonials = collect();

        foreach($homepageTestimonials as $testimonial){

            $testimonials->push([
                "quote" => $testimonial->testimonial->quote,
                "author" => $testimonial->testimonial->author,
                "status" => "active"
            ]);
        }        

        return $testimonials;
    }
}