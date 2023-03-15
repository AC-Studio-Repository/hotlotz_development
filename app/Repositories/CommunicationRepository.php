<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

class CommunicationRepository
{

    public function __construct(){

    }

    public function getCommunicationPreferences()
    {
        $preferences = [
            'Auction Updates',
            'Marketplace Updates', 
            'Events', 
            'Consignment & Valuation', 
            'Hotlotz Quarterly Newsletter'
        ];
        return $preferences;
    }
}