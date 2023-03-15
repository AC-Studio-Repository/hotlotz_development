<?php

namespace App\Modules\TickerDisplay\Http\Repositories;

use App\Modules\TickerDisplay\Models\TickerDisplay;

class TickerDisplayRepository
{
    public function __construct(TickerDisplay $ticker_display) {
        $this->ticker_display = $ticker_display;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->ticker_display
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
        return $this->ticker_display
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
        return $this->ticker_display->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->ticker_display
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->ticker_display->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->ticker_display->destroy($id);
        } else {
            return $this->ticker_display->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->ticker_display->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['title'] = $request['title'];
        $payload['description'] = $request['description'];
        $payload['link'] = $request['link'];

        return $payload;
    }
}