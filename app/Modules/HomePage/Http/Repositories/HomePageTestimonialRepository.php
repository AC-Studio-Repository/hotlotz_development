<?php

namespace App\Modules\HomePage\Http\Repositories;

use App\Modules\HomePage\Models\HomePageTestimonial;

class HomePageTestimonialRepository
{
    public function __construct(HomePageTestimonial $home_page_testimonial) {
        $this->home_page_testimonial = $home_page_testimonial;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->home_page_testimonial
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        if ($withTrash) {
                            return $query->withEagerTrashed($eagerLoad);
                        } else {
                            return $query->with($eagerLoad);
                        }
                    })
                    ->when($paginateCount, function ($query, $role) use ($paginateCount) {
                        return $query->paginate($paginateCount);
                    }, function ($query) {
                        return $query->get();
                    });
    }
}