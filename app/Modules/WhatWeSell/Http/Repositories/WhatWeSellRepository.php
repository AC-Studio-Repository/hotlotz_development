<?php

namespace App\Modules\WhatWeSell\Http\Repositories;

use App\Modules\WhatWeSell\Models\WhatWeSell;

class WhatWeSellRepository
{
    public function __construct(WhatWeSell $whatWeSell) {
        $this->whatWeSell = $whatWeSell;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->whatWeSell
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
        return $this->whatWeSell
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
        return $this->whatWeSell->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->whatWeSell
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->whatWeSell->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->whatWeSell->destroy($id);
        } else {
            return $this->whatWeSell->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->whatWeSell->withTrashed()->find($id)->restore();
    }
}