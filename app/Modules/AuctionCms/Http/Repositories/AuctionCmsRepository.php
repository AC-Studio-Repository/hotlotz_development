<?php

namespace App\Modules\AuctionCms\Http\Repositories;

use App\Modules\AuctionCms\Models\AuctionCms;

class AuctionCmsRepository
{
    public function __construct(AuctionCms $AuctionCms) {
        $this->auctionCms = $AuctionCms;
    }

    public function create($payload) {
        return $this->auctionCms->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->auctionCms
                    ->find($id)->update($payload);
    }
}