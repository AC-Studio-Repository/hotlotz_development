<?php

namespace App\Modules\ProfessionalValuations\Http\Repositories;

use App\Modules\ProfessionalValuations\Models\ProfessionalValuations;

class ProfessionalValuationsRepository
{
    public function __construct(ProfessionalValuations $ProfessionalValuations) {
        $this->ProfessionalValuations = $ProfessionalValuations;
    }

    public function create($payload) {
        return $this->ProfessionalValuations->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->ProfessionalValuations
                    ->find($id)->update($payload);
    }
}