<?php

namespace App\Modules\OurTeam\Http\Repositories;

use App\Modules\OurTeam\Models\OurTeamInfo;

class OurTeamInfoRepository
{
    public function __construct(OurTeamInfo $ourTeamInfo) {
        $this->ourTeamInfo = $ourTeamInfo;
    }

    public function create($payload) {
        return $this->ourTeamInfo->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->ourTeamInfo
                    ->find($id)->update($payload);
    }
}