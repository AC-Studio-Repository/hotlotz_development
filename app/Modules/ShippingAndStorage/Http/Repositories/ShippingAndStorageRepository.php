<?php

namespace App\Modules\ShippingAndStorage\Http\Repositories;

use App\Modules\ShippingAndStorage\Models\ShippingAndStorage;

class ShippingAndStorageRepository
{
    public function __construct(ShippingAndStorage $ShippingAndStorage) {
        $this->shippingAndStorage = $ShippingAndStorage;
    }

    public function create($payload) {
        return $this->shippingAndStorage->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->shippingAndStorage
                    ->find($id)->update($payload);
    }
}