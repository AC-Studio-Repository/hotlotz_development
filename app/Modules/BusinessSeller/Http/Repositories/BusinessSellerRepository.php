<?php

namespace App\Modules\BusinessSeller\Http\Repositories;

use App\Modules\BusinessSeller\Models\BusinessSeller;

class BusinessSellerRepository
{
    public function __construct(BusinessSeller $businessSeller) {
        $this->businessSeller = $businessSeller;
    }

    public function create($payload) {
        return $this->businessSeller->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->businessSeller
                    ->find($id)->update($payload);
    }
}