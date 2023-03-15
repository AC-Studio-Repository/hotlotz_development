<?php

namespace App\Modules\HomeContent\Http\Repositories;

use App\Modules\HomeContent\Models\HomeContent;

class HomeContentRepository
{
    public function __construct(HomeContent $HomeContent) {
        $this->homeContent = $HomeContent;
    }

    public function create($payload) {
        return $this->homeContent->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->homeContent
                    ->find($id)->update($payload);
    }
}