<?php

namespace App\Modules\AuctionMainPage\Http\Repositories;

use App\Modules\AuctionMainPage\Models\PastCataloguesMain;

class PastCataloguesMainRepository
{
    public function __construct(PastCataloguesMain $pastCataloguesMain) {
        $this->pastCataloguesMain = $pastCataloguesMain;
    }

    public function create($payload) {
        return $this->pastCataloguesMain->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->pastCataloguesMain
                    ->find($id)->update($payload);
    }
}