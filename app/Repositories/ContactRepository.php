<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

class ContactRepository
{

    public function __construct(){

    }

    public function getKeyContacts()
    {
        if (request()->path('/services/valuations')) {
            $contact = collect([
                [
                    'photoPath' => 'ecommerce/images/team/member-1.png',
                    'contactName' => 'Matthew Elton',
                    'contactPosition' => 'Founder | Managing Director | Auctioneer',
                    'contactEmail' => 'matthew@hotlotz.com',
                ]
            ]);
            return $contact;
        } else {
            $contact = collect([
                [
                    'photoPath' => 'ecommerce/images/Services/what-we-sell/antiquemap/ANTIQUEMAPS_keycontact.jpg',
                    'contactName' => 'Constance de Villaine',
                    'contactPosition' => 'Valuer',
                    'contactEmail' => 'constance@hotlotz.com',
                ]
            ]);
            return $contact;
        }
    }
}