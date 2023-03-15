<?php

namespace App\Modules\ShippingAndStorage\Http\Repositories;

use App\Modules\ShippingAndStorage\Models\ShippingAndStorageBlog;

class ShippingAndStorageBlogRepository
{
    public function __construct(ShippingAndStorageBlog $shippingAndStorageBlog) {
        $this->shippingAndStorageBlog = $shippingAndStorageBlog;
    }

    public function create($payload) {
        return $this->shippingAndStorageBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->shippingAndStorageBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->shippingAndStorageBlog->destroy($id);
    }
}