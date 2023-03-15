<?php

namespace App\Modules\MarketplaceBanner\Http\Repositories;

use App\Modules\MarketplaceBanner\Models\MarketplaceBanner;

class MarketplaceBannerRepository
{
    public function __construct(MarketplaceBanner $marketplace_banner) {
        $this->marketplace_banner = $marketplace_banner;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->marketplace_banner
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
        return $this->marketplace_banner
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
        return $this->marketplace_banner->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->marketplace_banner
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->marketplace_banner->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->marketplace_banner->destroy($id);
        } else {
            return $this->marketplace_banner->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->marketplace_banner->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['order'] = 1;

        return $payload;
    }
}