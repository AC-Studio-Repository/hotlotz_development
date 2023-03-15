<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

use App\Modules\HomePageRandomText\Models\HomePageRandomText;
use App\Modules\TickerDisplay\Models\TickerDisplay;

class TickerRepository
{

    public function __construct(){

    }

    public function getTickerText(){
        $texts = collect();
        $ticker_displays = TickerDisplay::orderBy('order')->get();

        foreach($ticker_displays as $text){

            $texts->push([
                "title" => $text->title,
                "description" => $text->description,
                "link" => $text->link
            ]);
        }        

        return $texts;
    }
}