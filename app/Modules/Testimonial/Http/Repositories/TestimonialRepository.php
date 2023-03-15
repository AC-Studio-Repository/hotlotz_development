<?php

namespace App\Modules\Testimonial\Http\Repositories;

use App\Modules\Testimonial\Models\Testimonial;

class TestimonialRepository
{
    public function __construct(Testimonial $testimonial) {
        $this->testimonial = $testimonial;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->testimonial
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

    public function show($column, $value, $eagerLoad = [], $withTrash = false, $returnMany = false) {
        return $this->testimonial
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
                    ->where($column, $value)
                    ->when($returnMany, function ($query, $role) {
                        return $query->get();
                    }, function ($query) {
                        return $query->first();
                    });
    }

    public function create($payload) {
        return $this->testimonial->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->testimonial
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->testimonial->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->testimonial->destroy($id);
        } else {
            return $this->testimonial->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->testimonial->withTrashed()->find($id)->restore();
    }
}