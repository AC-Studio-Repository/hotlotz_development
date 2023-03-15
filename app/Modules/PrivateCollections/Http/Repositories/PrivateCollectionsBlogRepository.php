<?php

namespace App\Modules\PrivateCollections\Http\Repositories;

use App\Modules\PrivateCollections\Models\PrivateCollectionsBlog;

class PrivateCollectionsBlogRepository
{
    public function __construct(PrivateCollectionsBlog $privateCollectionsBlog) {
        $this->privateCollectionsBlog = $privateCollectionsBlog;
    }

    public function create($payload) {
        return $this->privateCollectionsBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->privateCollectionsBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->privateCollectionsBlog->destroy($id);
    }
}