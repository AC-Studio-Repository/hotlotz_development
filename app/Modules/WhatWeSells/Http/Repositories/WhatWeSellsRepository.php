<?php

namespace App\Modules\WhatWeSells\Http\Repositories;

use App\Modules\WhatWeSells\Models\WhatWeSells;

class WhatWeSellsRepository
{
    public function __construct(WhatWeSells $what_we_sell) {
        $this->what_we_sell = $what_we_sell;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->what_we_sell
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
        return $this->what_we_sell
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
        return $this->what_we_sell->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->what_we_sell
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->what_we_sell->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->what_we_sell->destroy($id);
        } else {
            return $this->what_we_sell->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->what_we_sell->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['category_id'] = $request['category_id'];
        $payload['caption'] = $request['caption'];
        $payload['price_status'] = $request['price_status'] ?? 'N';
        $payload['title'] = $request['title'];
        $payload['description'] = $request['description'];
        $payload['key_contact_1'] = $request['key_contact_1'];
        $payload['key_contact_2'] = $request['key_contact_2'];

        return $payload;
    }
}