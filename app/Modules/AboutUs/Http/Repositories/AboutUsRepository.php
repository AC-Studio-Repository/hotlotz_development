<?php

namespace App\Modules\AboutUs\Http\Repositories;

use App\Modules\AboutUs\Models\AboutUs;

class AboutUsRepository
{
    public function __construct(AboutUs $AboutUs) {
        $this->aboutus = $AboutUs;
    }

    public function create($payload) {
        return $this->aboutus->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->aboutus
                    ->find($id)->update($payload);
    }
}