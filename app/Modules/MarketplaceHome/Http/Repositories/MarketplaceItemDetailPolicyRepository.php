<?php

namespace App\Modules\MarketplaceHome\Http\Repositories;

use App\Modules\MarketplaceHome\Models\MarketplaceItemDetailPolicy;

class MarketplaceItemDetailPolicyRepository
{
    public function __construct(MarketplaceItemDetailPolicy $info) {
        $this->info = $info;
    }

    public function create($payload) {
        return $this->info->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->info
                    ->find($id)->update($payload);
    }
}