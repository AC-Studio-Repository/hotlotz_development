<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;
use App\Modules\BlogPost\Models\BlogPost;

class EventRepository
{

    public function __construct(){

    }

    public function getEvents(){
        // $item = collect([
        //     [
        //         "slug" => "valuations-in-panang",
        //         "photoPath" => "ecommerce/images/articles-and-events/event-1.png",
        //         "title" => "Valuations in Panang",
        //         "time" => "MONDAY 11 DECEMBER",
        //         "route" => "{{route('event-1')}}"
        //     ],
        //     [
        //         "slug" => "48-mason",
        //         "photoPath" => "ecommerce/images/articles-and-events/event-2.png",
        //         "title" => "48 Mason",
        //         "time" => "MONDAY 11 DECEMBER",
        //         "route" => "{{route('event-2')}}"
        //     ]
        // ]);

        $blog_posts = BlogPost::orderBy('order')->limit(12)->get();
        $events = [];
        foreach ($blog_posts as $key => $post) {
            $events[] = [
                "title" => $post->title,
                "photo" => $post->full_path,
                "date" => date_format(date_create($post->post_date),'M d, Y'),
                "link_name" => $post->link_name,
                "link" => $post->link,
            ];
        }
        return $events;
    }

    public function getEventDetail($slug){
        $item = collect(
            [
                "slug" => "valuations-in-panang",
                "photoPath" => "ecommerce/images/articles-and-events/event-1.png",
                "title" => "Valuations in Panang",
                "time" => "MONDAY 11 DECEMBER",
            ]
        );
        return $item;
    }
}