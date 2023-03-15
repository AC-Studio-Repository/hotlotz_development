<?php

namespace App\Modules\Customer\Repositories;

use App\Modules\Customer\Models\CustomerFavourites;

class CustomerFavouritesRepository
{
    public function __construct(CustomerFavourites $customerFavourites)
    {
        $this->customerFavourites = $customerFavourites;
    }

    public function create($payload)
    {
        return $this->customerFavourites->create($payload);
    }

    public function update($id, $payload, $withTrash = false)
    {
        return $this->customerFavourites
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }
}
