<?php

	Route::name('main_banners.')->group(function () {	    
	    Route::post('/main_banners/main_banner_reordering', 'MainBannerController@mainBannerReordering')->name('main_banner_reordering');
	});

    Route::resource('/main_banners', 'MainBannerController');

