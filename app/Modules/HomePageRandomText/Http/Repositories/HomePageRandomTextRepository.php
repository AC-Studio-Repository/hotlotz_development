<?php

namespace App\Modules\HomePageRandomText\Http\Repositories;

use App\Modules\HomePageRandomText\Models\HomePageRandomText;

class HomePageRandomTextRepository
{
    public function __construct(HomePageRandomText $homePageRandomText) {
        $this->homePageRandomText = $homePageRandomText;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->homePageRandomText
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
        return $this->homePageRandomText
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
        return $this->homePageRandomText->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->homePageRandomText
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->homePageRandomText->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->homePageRandomText->destroy($id);
        } else {
            return $this->homePageRandomText->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->homePageRandomText->withTrashed()->find($id)->restore();
    }
}