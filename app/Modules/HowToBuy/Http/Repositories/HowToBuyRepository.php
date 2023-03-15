<?php

namespace App\Modules\HowToBuy\Http\Repositories;

use App\Modules\HowToBuy\Models\HowToBuy;

class HowToBuyRepository
{
    public function __construct(HowToBuy $howtobuy) {
        $this->howtobuy = $howtobuy;
    }

    public function create($payload) {
        return $this->howtobuy->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->howtobuy
                    ->find($id)->update($payload);
    }
}