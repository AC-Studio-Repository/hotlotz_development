<?php

	Route::name('blog_articles.')->group(function () {	    
	    Route::post('/blog_articles/blog_article_reordering', 'BlogArticleController@blogArticleReordering')->name('blog_article_reordering');
	});

    Route::resource('/blog_articles', 'BlogArticleController');