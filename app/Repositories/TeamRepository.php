<?php

namespace App\Repositories;

use App\Modules\OurTeam\Models\OurTeam;

class TeamRepository
{

    public function __construct(){

    }

    public function getOurTeamData(){

        $our_teams = OurTeam::orderBy('order')->get();

        $team = [];
        foreach($our_teams as $cms){
            $team[] = [
                'id' => $cms->title,
                "name" => $cms->name,
                "title" => $cms->position,
                "email" => $cms->contact_email,
                "file_path" => $cms->full_path,
                "motto" => $cms->motto,
                "file_path2" => $cms->full_path2,
                "experience" => $cms->experience,
                "fun_fact" => $cms->fun_fact,
            ];
        }
        // dd($team);
        
        return $team;
    }
}