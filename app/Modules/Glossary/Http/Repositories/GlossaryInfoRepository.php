<?php

namespace App\Modules\Glossary\Http\Repositories;

use App\Modules\Glossary\Models\GlossaryInfo;

class GlossaryInfoRepository
{
    public function __construct(GlossaryInfo $glossary) {
        $this->glossary = $glossary;
    }

    public function create($payload) {
        return $this->glossary->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->glossary
                    ->find($id)->update($payload);
    }
}