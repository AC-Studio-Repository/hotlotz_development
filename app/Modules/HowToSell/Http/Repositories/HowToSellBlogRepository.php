<?php

namespace App\Modules\HowToSell\Http\Repositories;

use App\Modules\HowToSell\Models\HowToSellBlog;

class HowToSellBlogRepository
{
    public function __construct(HowToSellBlog $howtosellBlog) {
        $this->howtosellBlog = $howtosellBlog;
    }

    public function create($payload) {
        return $this->howtosellBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->howtosellBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->howtosellBlog->destroy($id);
    }
}