<?php

namespace App\Modules\AuctionMainPage\Http\Repositories;

use App\Modules\AuctionMainPage\Models\AuctionResultsMain;

class AuctionResultsMainRepository
{
    public function __construct(AuctionResultsMain $auctionResultMain) {
        $this->auctionResultMain = $auctionResultMain;
    }

    public function create($payload) {
        return $this->auctionResultMain->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->auctionResultMain
                    ->find($id)->update($payload);
    }
}