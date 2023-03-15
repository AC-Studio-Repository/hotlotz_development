<?php

namespace App\Modules\AuctionCms\Http\Repositories;

use App\Modules\AuctionCms\Models\AuctionCmsBlog;

class AuctionCmsBlogRepository
{
    public function __construct(AuctionCmsBlog $auctionCmsBlog) {
        $this->auctionCmsBlog = $auctionCmsBlog;
    }

    public function create($payload) {
        return $this->auctionCmsBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->auctionCmsBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->auctionCmsBlog->destroy($id);
    }
}