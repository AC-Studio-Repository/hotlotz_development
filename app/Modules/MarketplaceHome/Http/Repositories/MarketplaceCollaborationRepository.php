<?php

namespace App\Modules\MarketplaceHome\Http\Repositories;

use App\Modules\MarketplaceHome\Models\MarketplaceCollaboration;

class MarketplaceCollaborationRepository
{
    public function __construct(MarketplaceCollaboration $home_page) {
        $this->home_page = $home_page;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->home_page
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
        return $this->home_page
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
        return $this->home_page->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->home_page
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->home_page->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->home_page->destroy($id);
        } else {
            return $this->home_page->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->home_page->withTrashed()->find($id)->restore();
    }
}