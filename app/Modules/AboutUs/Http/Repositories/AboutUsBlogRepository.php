<?php

namespace App\Modules\AboutUs\Http\Repositories;

use App\Modules\AboutUs\Models\AboutUsBlog;

class AboutUsBlogRepository
{
    public function __construct(AboutUsBlog $aboutUsBlog) {
        $this->aboutusBlog = $aboutUsBlog;
    }

    public function create($payload) {
        return $this->aboutusBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->aboutusBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->aboutusBlog->destroy($id);
    }
}