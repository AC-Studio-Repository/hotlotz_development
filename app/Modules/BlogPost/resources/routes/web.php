<?php

	Route::name('blog_posts.')->group(function () {	    
	    Route::post('/blog_posts/blog_post_reordering', 'BlogPostController@blogPostReordering')->name('blog_post_reordering');
	});

    Route::resource('/blog_posts', 'BlogPostController');