<?php

namespace App\Modules\MainBanner\Http\Repositories;

use App\Modules\MainBanner\Models\MainBanner;

class MainBannerRepository
{
    public function __construct(MainBanner $main_banner) {
        $this->main_banner = $main_banner;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->main_banner
                    ->orderBy('order','asc')
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
        return $this->main_banner
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
        return $this->main_banner->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->main_banner
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->main_banner->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->main_banner->destroy($id);
        } else {
            return $this->main_banner->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->main_banner->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['main_title'] = $request['main_title'];
        $payload['sub_title'] = $request['sub_title'];
        $payload['link_name'] = $request['link_name'];
        $payload['link'] = $request['link'];
        $payload['position'] = $request['position'];
        $payload['color'] = $request['color'];

        return $payload;
    }
}