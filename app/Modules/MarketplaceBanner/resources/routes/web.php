<?php

	Route::name('marketplace_banners.')->group(function () {	    
	    Route::post('/marketplace_banners/marketplace_banner_reordering', 'MarketplaceBannerController@marketplaceBannerReordering')->name('marketplace_banner_reordering');
	});

    Route::resource('/marketplace_banners', 'MarketplaceBannerController');

