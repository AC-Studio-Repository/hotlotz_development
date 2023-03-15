<?php

namespace App\Repositories;

use App\Modules\InternalAdvert\Models\InternalAdvert;

class InternalAdvertRepository
{
    public function __construct()
    {
    }

    public function getRandomInternalAds($count=2){
        return InternalAdvert::inRandomOrder()->limit($count)->get();
    }
}
