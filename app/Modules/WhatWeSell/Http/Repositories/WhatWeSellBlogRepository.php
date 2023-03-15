<?php

namespace App\Modules\WhatWeSell\Http\Repositories;

use App\Modules\WhatWeSell\Models\WhatWeSellBlog;

class WhatWeSellBlogRepository
{
    public function __construct(WhatWeSellBlog $whatWeSellBlog) {
        $this->whatWeSellBlog = $whatWeSellBlog;
    }

    public function create($payload) {
        return $this->whatWeSellBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->whatWeSellBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->whatWeSellBlog->destroy($id);
    }
}