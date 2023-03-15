<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

use App\Modules\Policy\Models\Policy;
use App\Modules\Careers\Models\Careers;
use App\Modules\MediaResource\Models\MediaResourcePressRelease;

class FooterRepository
{

    public function __construct(){

    }

    public function getPolicy(){
        $policies = collect();

        foreach(Policy::all() as $key=>$policy){
            $status = '';
            $file_link = '#';
            if($key == 0){
                $status = 'active';
            }

            if($policy->full_path != null)
            {
                $file_link = $policy->full_path;
            }
            $policies->push([
                "id" => $policy->id,
                "menu_name" => $policy->menu_name,
                "title" => $policy->title,
                "content" => $policy->content,
                "full_path" => $file_link,
                "status" => $status
            ]);
        }        

        return $policies;
    }

    public function getCareers(){
        $careers = collect();

        foreach(Careers::all() as $key=>$career){
            $status = '';
            $file_link = '#';
        
            if($career->file_path != null)
            {
                $file_link = $career->file_path;
            }
            $careers->push([
                "id" => $career->id,
                "position" => $career->position,
                "posts" => $career->posts,
                "expreience_level" => $career->expreience_level,
                "file_path" => $file_link
            ]);
        }        

        return $careers;
    }

    public function getMediaResource(){
        $resources = collect();

        foreach(MediaResourcePressRelease::all() as $key=>$resource){
            $status = '';
            $file_link = '#';
            $show_text = '';
            $string = strip_tags($resource->title);
            if (strlen($string) > 50) {
                $stringCut = substr($string, 0, 20);
                $endPoint = strrpos($stringCut, ' ');

                //if the string doesn't contain any space then it will cut without word basis.
                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $show_text = $string;
            }else{
                $show_text = $resource->title;
            }
        
            if($resource->file_path != null)
            {
                $file_link = $resource->file_path;
            }
            $resources->push([
                "id" => $resource->id,
                "date" => $resource->display_date,
                "text" => $show_text,
                "link" => $file_link
            ]);
        }        

        return $resources;
    }
}