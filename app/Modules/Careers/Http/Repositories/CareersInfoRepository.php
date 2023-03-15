<?php

namespace App\Modules\Careers\Http\Repositories;

use App\Modules\Careers\Models\CareersInfo;

class CareersInfoRepository
{
    public function __construct(CareersInfo $careers) {
        $this->careers = $careers;
    }

    public function create($payload) {
        return $this->careers->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->careers
                    ->find($id)->update($payload);
    }
}