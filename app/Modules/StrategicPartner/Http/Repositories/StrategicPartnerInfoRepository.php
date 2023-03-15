<?php

namespace App\Modules\StrategicPartner\Http\Repositories;

use App\Modules\StrategicPartner\Models\StrategicPartnerInfo;

class StrategicPartnerInfoRepository
{
    public function __construct(StrategicPartnerInfo $strategicPartnerInfo) {
        $this->strategicPartnerInfo = $strategicPartnerInfo;
    }

    public function create($payload) {
        return $this->strategicPartnerInfo->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->strategicPartnerInfo
                    ->find($id)->update($payload);
    }
}