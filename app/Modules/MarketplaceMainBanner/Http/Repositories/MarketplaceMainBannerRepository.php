<?php

namespace App\Modules\MarketplaceMainBanner\Http\Repositories;

use App\Modules\MarketplaceMainBanner\Models\MarketplaceMainBanner;

class MarketplaceMainBannerRepository
{
    public function __construct(MarketplaceMainBanner $marketplaceMainBanner) {
        $this->marketplaceMainBanner = $marketplaceMainBanner;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->marketplaceMainBanner
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
        return $this->marketplaceMainBanner
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
        return $this->marketplaceMainBanner->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->marketplaceMainBanner
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->marketplaceMainBanner->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->marketplaceMainBanner->destroy($id);
        } else {
            return $this->marketplaceMainBanner->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->marketplaceMainBanner->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['caption'] = $request->caption;
        $payload['learn_more'] = $request->learn_more;

        return $payload;
    }
}