<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

use App\Modules\WhatWeSell\Models\WhatWeSell;
use App\Modules\WhatWeSells\Models\WhatWeSells;

class WhatWeSellRepository
{

    public function __construct(){

    }

    public function getWhatWeSell(){
        $whatwesellItems = WhatWeSells::orderBy('order')->get();

        $data = [];
        if (!$whatwesellItems->isEmpty()) {
            foreach ($whatwesellItems as $key => $value) {
                $data[] = [
                    // 'image' => $value->list_image_file_path,
                    'image' => $value->full_path,
                    'title' => $value->title,
                    'url' => route('services.whatwesellItems', $value->id)
                ];
            }
            $data = collect($data);
        }

        return $data;
    }
}