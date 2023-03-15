<?php

namespace App\Modules\EmailTemplate\Http\Repositories;

use App\Modules\EmailTemplate\Models\EmailTemplate;

class EmailTemplateRepository
{
    public function __construct(EmailTemplate $email_template) {
        $this->email_template = $email_template;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->email_template
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
        return $this->email_template
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
        return $this->email_template->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->email_template
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->email_template->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->email_template->destroy($id);
        } else {
            return $this->email_template->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->email_template->withTrashed()->find($id)->restore();
    }
}