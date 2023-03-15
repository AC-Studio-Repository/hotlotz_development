<?php

namespace App\Modules\StrategicPartner\Http\Repositories;

use App\Modules\StrategicPartner\Models\StrategicPartner;

class StrategicPartnerRepository
{
    public function __construct(StrategicPartner $strategic_partner) {
        $this->strategic_partner = $strategic_partner;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->strategic_partner
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
        return $this->strategic_partner
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
        return $this->strategic_partner->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->strategic_partner
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->strategic_partner->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->strategic_partner->destroy($id);
        } else {
            return $this->strategic_partner->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->strategic_partner->withTrashed()->find($id)->restore();
    }
}