<?php

namespace App\Modules\Customer\Repositories;

use App\Modules\Customer\Models\CustomerInterests;

class CustomerInterestRepository
{
    public function __construct(CustomerInterests $customerInterests)
    {
        $this->customerInterests = $customerInterests;
    }

    public function create($payload)
    {
        return $this->customerInterests->create($payload);
    }

    public function update($id, $payload, $withTrash = false)
    {
        return $this->customerInterests
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }
}
