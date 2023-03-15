<?php

Route::name('business_sellers.')->group(function () {
    Route::get('/business_sellers/editcontent', 'BusinessSellerController@editcontent')->name('editcontent');
    Route::post('/business_sellers/banner_image_upload', 'BusinessSellerController@banner_image_upload')->name('banner_image_upload');
    Route::post('/business_sellers/updateContent', 'BusinessSellerController@updateContent')->name('updateContent');
});

Route::resource('/business_sellers', 'BusinessSellerController');