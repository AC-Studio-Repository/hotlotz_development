<?php

namespace App\Modules\HowToSell\Http\Repositories;

use App\Modules\HowToSell\Models\HowToSell;

class HowToSellRepository
{
    public function __construct(HowToSell $HowToSell) {
        $this->howtosell = $HowToSell;
    }

    public function create($payload) {
        return $this->howtosell->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->howtosell
                    ->find($id)->update($payload);
    }
}