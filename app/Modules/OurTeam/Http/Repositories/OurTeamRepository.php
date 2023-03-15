<?php

namespace App\Modules\OurTeam\Http\Repositories;

use App\Modules\OurTeam\Models\OurTeam;

class OurTeamRepository
{
    public function __construct(OurTeam $ourTeam) {
        $this->ourTeam = $ourTeam;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->ourTeam
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
        return $this->ourTeam
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
        return $this->ourTeam->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->ourTeam
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->ourTeam->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->ourTeam->destroy($id);
        } else {
            return $this->ourTeam->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->ourTeam->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['name'] = $request->name;
        $payload['position'] = $request->position;
        $payload['contact_email'] = $request->contact_email;
        $payload['motto'] = $request->motto;
        $payload['experience'] = $request->experience;
        $payload['fun_fact'] = $request->fun_fact;

        // $payload['profile_image'] = $request->hide_team_image_ids;
        // $payload['full_path'] = $request->hide_team_full_path_ids;

        // $payload['profile_image2'] = $request->hide_team_image2_ids;
        // $payload['full_path2'] = $request->hide_team_full_path2_ids;

        return $payload;
    }
}