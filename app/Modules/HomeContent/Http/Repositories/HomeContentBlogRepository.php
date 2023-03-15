<?php

namespace App\Modules\HomeContent\Http\Repositories;

use App\Modules\HomeContent\Models\HomeContentBlog;

class HomeContentBlogRepository
{
    public function __construct(HomeContentBlog $homeContentBlog) {
        $this->homeContentBlog = $homeContentBlog;
    }

    public function create($payload) {
        return $this->homeContentBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->homeContentBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->homeContentBlog->destroy($id);
    }
}