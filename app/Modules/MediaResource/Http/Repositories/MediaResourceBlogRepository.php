<?php

namespace App\Modules\MediaResource\Http\Repositories;

use App\Modules\MediaResource\Models\MediaResourceBlog;

class MediaResourceBlogRepository
{
    public function __construct(MediaResourceBlog $mediaResourceBlog) {
        $this->mediaResourceBlog = $mediaResourceBlog;
    }

    public function create($payload) {
        return $this->mediaResourceBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->mediaResourceBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->mediaResourceBlog->destroy($id);
    }
}