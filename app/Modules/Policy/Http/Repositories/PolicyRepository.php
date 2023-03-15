<?php

namespace App\Modules\Policy\Http\Repositories;

use App\Modules\Policy\Models\Policy;

class PolicyRepository
{
    public function __construct(Policy $policy) {
        $this->policy = $policy;
    }

    public function all($eagerLoad = [], $withTrash = false, $paginateCount = 0) {
        return $this->policy
                    // ->when($withTrash, function ($query) {
                    //     return $query->withTrashed();
                    // })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        // if ($withTrash) {
                        //     return $query->withEagerTrashed($eagerLoad);
                        // } else {
                            return $query->with($eagerLoad);
                        // }
                    })
                    ->when($paginateCount, function ($query, $role) use ($paginateCount) {
                        return $query->paginate($paginateCount);
                    }, function ($query) {
                        return $query->get();
                    });
    }

    public function show($column, $value, $eagerLoad = [], $withTrash = false, $returnMany = false) {
        return $this->policy
                    // ->when($withTrash, function ($query) {
                    //     return $query->withTrashed();
                    // })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        // if ($withTrash) {
                        //     return $query->withEagerTrashed($eagerLoad);
                        // } else {
                            return $query->with($eagerLoad);
                        // }
                    })
                    ->where($column, $value)
                    ->when($returnMany, function ($query, $role) {
                        return $query->get();
                    }, function ($query) {
                        return $query->first();
                    });
    }

    public function create($payload) {
        return $this->policy->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->policy
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->policy->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->policy->destroy($id);
        } else {
            return $this->policy->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->policy->withTrashed()->find($id)->restore();
    }

    public function getPolicyDocumentData($id)
    {
        $policy = Policy::find($id);

        $initialpreview = [];
        $initialpreviewconfig = [];
        if($policy) {
            $ext = pathinfo(asset($policy->file_path), PATHINFO_EXTENSION);
            if (in_array($ext, ["jpg", "jpeg", "png"])) {
                $ext = 'image';
            }

            $initialpreview[] = $policy->full_path;
            $initialpreviewconfig[] = [
                'caption'=>'policy',
                'type' => $ext,
                // 'size'=>'57071', 'width'=>"263px", 'height'=>"217px",
                'url'=>'/manage/policies/'.$policy->id.'/document_delete',
                'key'=>$policy->id,
                'extra' => ['_token'=>csrf_token()]
            ];
        }

        return array(
            'initialpreview'=>$initialpreview,
            'initialpreviewconfig'=>$initialpreviewconfig,
        );
    }
}