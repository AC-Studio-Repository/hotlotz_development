<?php

namespace App\Modules\PrivateCollections\Http\Repositories;

use App\Modules\PrivateCollections\Models\PrivateCollections;

class PrivateCollectionsRepository
{
    public function __construct(PrivateCollections $privateCollections) {
        $this->privateCollections = $privateCollections;
    }

    public function create($payload) {
        return $this->privateCollections->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->privateCollections
                    ->find($id)->update($payload);
    }
}