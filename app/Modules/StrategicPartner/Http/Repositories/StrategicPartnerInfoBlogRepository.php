<?php

namespace App\Modules\StrategicPartner\Http\Repositories;

use App\Modules\StrategicPartner\Models\StrategicPartnerInfoBlog;

class StrategicPartnerInfoBlogRepository
{
    public function __construct(StrategicPartnerInfoBlog $strategicPartnerInfoBlog) {
        $this->strategicPartnerInfoBlog = $strategicPartnerInfoBlog;
    }

    public function create($payload) {
        return $this->strategicPartnerInfoBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->strategicPartnerInfoBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->strategicPartnerInfoBlog->destroy($id);
    }
}