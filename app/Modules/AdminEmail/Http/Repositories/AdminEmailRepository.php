<?php

namespace App\Modules\AdminEmail\Http\Repositories;

use App\Modules\AdminEmail\Models\AdminEmail;

class AdminEmailRepository
{
    public function __construct(AdminEmail $admin_email) {
        $this->admin_email = $admin_email;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->admin_email
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
        return $this->admin_email
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
        return $this->admin_email->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->admin_email
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->admin_email->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->admin_email->destroy($id);
        } else {
            return $this->admin_email->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->admin_email->withTrashed()->find($id)->restore();
    }
}