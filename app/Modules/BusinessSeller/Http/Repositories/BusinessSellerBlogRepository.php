<?php

namespace App\Modules\BusinessSeller\Http\Repositories;

use App\Modules\BusinessSeller\Models\BusinessSellerBlog;

class BusinessSellerBlogRepository
{
    public function __construct(BusinessSellerBlog $businessSellerBlog) {
        $this->businessSellerBlog = $businessSellerBlog;
    }

    public function create($payload) {
        return $this->businessSellerBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->businessSellerBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->businessSellerBlog->destroy($id);
    }
}