<?php

namespace App\Modules\SysConfig\Http\Repositories;

use App\Modules\SysConfig\Models\SysConfig;

class SysConfigRepository
{
    public function __construct(SysConfig $sys_config) {
        $this->sys_config = $sys_config;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->sys_config
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
        return $this->sys_config
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
        return $this->sys_config->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->sys_config
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->sys_config->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->sys_config->destroy($id);
        } else {
            return $this->sys_config->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->sys_config->withTrashed()->find($id)->restore();
    }
}