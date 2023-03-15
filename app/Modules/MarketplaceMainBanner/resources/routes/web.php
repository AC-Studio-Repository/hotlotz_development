<?php

Route::name('marketplace_main_banners.')->group(function () {
    Route::get('/marketplace_main_banners/showlist', 'MarketplaceMainBannerController@showlist')->name('showlist');

    Route::post('/marketplace_main_banners/team_member_reordering', 'MarketplaceMainBannerController@marketplaceMainBannerReordering')->name('team_member_reordering');
});

Route::resource('/marketplace_main_banners', 'MarketplaceMainBannerController');