<?php

namespace App\Modules\ProfessionalValuations\Http\Repositories;

use App\Modules\ProfessionalValuations\Models\ProfessionalValuationsBlog;

class ProfessionalValuationsBlogRepository
{
    public function __construct(ProfessionalValuationsBlog $professionalValuationsBlog) {
        $this->professionalValuationsBlog = $professionalValuationsBlog;
    }

    public function create($payload) {
        return $this->professionalValuationsBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->professionalValuationsBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->professionalValuationsBlog->destroy($id);
    }
}