<?php

Route::name('marketplace_home_banners.')->group(function () {
    Route::post('/marketplace_home_banners/marketplace_home_banner_upload', 'MarketplaceHomeBannerController@marketplace_home_banner_upload')->name('marketplace_home_banner_upload');
    Route::post('/marketplace_home_banners/storeInfo', 'MarketplaceHomeBannerController@storeInfo')->name('storeInfo');
});

Route::resource('/marketplace_home_banners', 'MarketplaceHomeBannerController');