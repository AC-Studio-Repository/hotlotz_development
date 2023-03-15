<?php

use Illuminate\Http\Request;

Route::name('home_pages.')->group(function () {
    Route::get('/home_pages/main_banner_list', 'HomePageController@main_banner_list')->name('main_banner_list');

    Route::get('/home_pages/main_banner_index', 'HomePageController@main_banner_index')->name('main_banner_index');
    Route::post('/home_pages/homepage_main_banner_upload', 'HomePageController@homepage_main_banner_upload')->name('homepage_main_banner_upload');
    Route::post('/home_pages/storeInfo', 'HomePageController@storeInfo')->name('storeInfo');

    Route::get('/home_pages/marketplace_banner_index', 'HomePageController@marketplace_banner_index')->name('marketplace_banner_index');
    Route::post('/home_pages/homepage_marketplace_banner_upload', 'HomePageController@homepage_marketplace_banner_upload')->name('homepage_marketplace_banner_upload');
    Route::post('/home_pages/storeMarketplaceBanner', 'HomePageController@storeMarketplaceBanner')->name('storeMarketplaceBanner');

    Route::get('/home_pages/showtestimonial', 'HomePageController@showtestimonial')->name('showtestimonial');
    Route::post('/home_pages/storeTestimonialAjax', 'HomePageController@storeTestimonialAjax')->name('storeTestimonialAjax');
});

Route::resource('/home_pages', 'HomePageController');

Route::post('/menu/store', function (Request $request) {
    session(['old_url' => $request->url]);
    return response()->json(array('status'=>1, 'message'=>'Save Old Url Successfully!'));
});
