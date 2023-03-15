<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

use App\Modules\WhatsNewArticleOne\Models\WhatsNewArticleOne;
use App\Modules\WhatsNewWelcome\Models\WhatsNewWelcome;
use App\Modules\WhatsNewBidBarometer\Models\WhatsNewBidBarometer;

class WhatsNewRepository
{

    public function __construct(){

    }

    public function getArtcleOneInfo(){
        $article_one = WhatsNewArticleOne::first();
        return $article_one;
    }

    public function getWelcomeInfo(){
        $welcome = WhatsNewWelcome::first();
        return $welcome;
    }

    public function getBidBarometerInfo(){
        $bid_barometer = WhatsNewBidBarometer::first();
        return $bid_barometer;
    }
}