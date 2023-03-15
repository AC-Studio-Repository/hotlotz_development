<?php

namespace App\Modules\WhatsNewBidBarometer\Http\Repositories;

use App\Modules\WhatsNewBidBarometer\Models\WhatsNewBidBarometer;

class WhatsNewBidBarometerRepository
{
    public function __construct(WhatsNewBidBarometer $whats_new_bid_barometer) {
        $this->whats_new_bid_barometer = $whats_new_bid_barometer;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->whats_new_bid_barometer
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
        return $this->whats_new_bid_barometer
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
        return $this->whats_new_bid_barometer->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->whats_new_bid_barometer
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->whats_new_bid_barometer->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->whats_new_bid_barometer->destroy($id);
        } else {
            return $this->whats_new_bid_barometer->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->whats_new_bid_barometer->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['title'] = $request['title'];
        $payload['description'] = $request['description'];
        $payload['link'] = $request['link'];

        return $payload;
    }
}