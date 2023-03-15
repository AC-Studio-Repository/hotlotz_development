<?php

namespace App\Modules\MarketplaceCms\Http\Repositories;

use App\Modules\MarketplaceCms\Models\MarketplaceCms;

class MarketplaceCmsRepository
{
    public function __construct(MarketplaceCms $MarketplaceCms) {
        $this->marketplaceCms = $MarketplaceCms;
    }

    public function create($payload) {
        return $this->marketplaceCms->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->marketplaceCms
                    ->find($id)->update($payload);
    }
}