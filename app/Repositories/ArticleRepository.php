<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;
use App\Modules\BlogArticle\Models\BlogArticle;

class ArticleRepository
{

    public function __construct(){

    }

    public function getArticleCategories(){
        $title = collect([
            "JUNE 2020", "NOVEMBER 2020",  "SEPTEMBER 2020"
        ]);
        return $title;
    }

    public function getArticles(){
        // $item = collect([
        //     [
        //         "image" => "ecommerce/images/Discover/articles-events/countrylife-logo.svg",
        //         "title" => "Toasting Online Success",
        //         "content" => "Ten of the best men watches on the market today",
        //         "date" => "June 2020",
        //         "category" => "June 2020"
        //     ],
        //     [
        //         "image" => "ecommerce/images/articles-and-events/article-2.png",
        //         "title" => "Up for auction : A house full of memories!",
        //         "content" => "Christopher Lanigan-O’Keeffe talks Cartier",
        //         "date" => "13th August 2019",
        //         "category" => "13th August 2019"
        //     ],
        //     [
        //         "image" => "ecommerce/images/articles-and-events/article-3.png",
        //         "title" => "Best Auction House Award",
        //         "content" => "Hotlotz Awarded as one othe best auction house.",
        //         "date" => "13th August 2019",
        //         "category" => "13th August 2019"
        //     ]
        // ]);
        
        $blog_articles = BlogArticle::orderBy('publication_date','desc')->get();
        $articles = [];
        foreach ($blog_articles as $key => $article) {
            $title = date_format(date_create($article->publication_date),'F Y');
            $articles[$title][] = [
                "title" => $article->title,
                "photo" => $article->full_path,
                "date" => date_format(date_create($article->publication_date),'d F Y'),
                "type" => $article->type,
                "article_file" => $article->article_full_path,
                "article_url" => $article->article_url,
            ];
        }
        return $articles;
    }
}