<?php

namespace App\Modules\MediaResource\Http\Repositories;

use App\Modules\MediaResource\Models\MediaResource;

class MediaResourceRepository
{
    public function __construct(MediaResource $mediaResource) {
        $this->mediaResource = $mediaResource;
    }

    public function create($payload) {
        return $this->mediaResource->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->mediaResource
                    ->find($id)->update($payload);
    }
}