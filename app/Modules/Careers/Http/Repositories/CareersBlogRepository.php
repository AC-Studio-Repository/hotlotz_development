<?php

namespace App\Modules\Careers\Http\Repositories;

use App\Modules\Careers\Models\CareersBlog;

class CareersBlogRepository
{
    public function __construct(CareersBlog $careersBlog) {
        $this->careersBlog = $careersBlog;
    }

    public function create($payload) {
        return $this->careersBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->careersBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->careersBlog->destroy($id);
    }
}