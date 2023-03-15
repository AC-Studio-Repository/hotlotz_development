<?php

namespace App\Modules\HotlotzConcierge\Http\Repositories;

use App\Modules\HotlotzConcierge\Models\HotlotzConcierge;

class HotlotzConciergeRepository
{
    public function __construct(HotlotzConcierge $HotlotzConcierge) {
        $this->hotlotzConcierge = $HotlotzConcierge;
    }

    public function create($payload) {
        return $this->hotlotzConcierge->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->hotlotzConcierge
                    ->find($id)->update($payload);
    }
}