<?php

namespace App\Modules\HowToBuy\Http\Repositories;

use App\Modules\HowToBuy\Models\HowToBuyBlog;

class HowToBuyBlogRepository
{
    public function __construct(HowToBuyBlog $howtobuyblog) {
        $this->howtobuyblog = $howtobuyblog;
    }

    public function create($payload) {
        return $this->howtobuyblog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->howtobuyblog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->howtobuyblog->destroy($id);
    }
}