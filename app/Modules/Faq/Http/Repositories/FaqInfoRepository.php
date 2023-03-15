<?php

namespace App\Modules\Faq\Http\Repositories;

use App\Modules\Faq\Models\FaqInfo;

class FaqInfoRepository
{
    public function __construct(FaqInfo $faqInfo) {
        $this->faqInfo = $faqInfo;
    }

    public function create($payload) {
        return $this->faqInfo->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->faqInfo
                    ->find($id)->update($payload);
    }
}