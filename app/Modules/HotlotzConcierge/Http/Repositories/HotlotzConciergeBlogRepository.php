<?php

namespace App\Modules\HotlotzConcierge\Http\Repositories;

use App\Modules\HotlotzConcierge\Models\HotlotzConciergeBlog;

class HotlotzConciergeBlogRepository
{
    public function __construct(HotlotzConciergeBlog $hotlotzConciergeBlog) {
        $this->hotlotzConciergeBlog = $hotlotzConciergeBlog;
    }

    public function create($payload) {
        return $this->hotlotzConciergeBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->hotlotzConciergeBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->hotlotzConciergeBlog->destroy($id);
    }
}