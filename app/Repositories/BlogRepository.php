<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;

class BlogRepository
{

    public function __construct(){

    }

    public function getBlogPosts(){
        $blogPosts = collect();

        $blogPosts['mainBlog'] = $this->getMainBlogPost();
        $blogPosts['sideBlog1'] = $this->getSideBlogPost();
        $blogPosts['sideBlog2'] = $this->getSideBlogPost();

        return $blogPosts;
    }

    public function getMainBlogPost(){
        $item = [
            "image" => "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/Auctions/landing/Landing_Page_Whats_New_image.jpg",
            "title" => "Main Blog Article",
            "url" => "#blog/main",
        ];

        return $item;
    }

    public function getSideBlogPost(){
        $item = [
            "image" => "https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/articles-and-events/event-1.png",
            "title" => "Side Blog 1",
            "url" => "#blog/sideblog/1",
        ];

        return $item;
    }
}
