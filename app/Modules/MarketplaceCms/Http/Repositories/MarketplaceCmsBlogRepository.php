<?php

namespace App\Modules\MarketplaceCms\Http\Repositories;

use App\Modules\MarketplaceCms\Models\MarketplaceCmsBlog;

class MarketplaceCmsBlogRepository
{
    public function __construct(MarketplaceCmsBlog $marketplaceCmsBlog) {
        $this->marketplaceCmsBlog = $marketplaceCmsBlog;
    }

    public function create($payload) {
        return $this->marketplaceCmsBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->marketplaceCmsBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->marketplaceCmsBlog->destroy($id);
    }
}