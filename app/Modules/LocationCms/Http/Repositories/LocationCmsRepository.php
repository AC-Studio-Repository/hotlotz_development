<?php

namespace App\Modules\LocationCms\Http\Repositories;

use App\Modules\LocationCms\Models\LocationCms;

class LocationCmsRepository
{
    public function __construct(LocationCms $locationCms) {
        $this->locationCms = $locationCms;
    }

    public function create($payload) {
        return $this->locationCms->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->locationCms
                    ->find($id)->update($payload);
    }
}