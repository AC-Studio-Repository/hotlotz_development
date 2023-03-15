<?php

	// Route::name('whats_new_article_ones.')->group(function () {	    
	//     Route::post('/whats_new_article_ones/whats_new_article_one_reordering', 'WhatsNewArticleOneController@whatsNewArticleOneReordering')->name('whats_new_article_one_reordering');
	// });

    Route::resource('/whats_new_article_ones', 'WhatsNewArticleOneController');

