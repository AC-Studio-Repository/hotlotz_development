<?php

Route::name('marketplace_cmss.')->group(function () {
    Route::get('/marketplace_cmss/editcontent', 'MarketplaceCmsController@editcontent')->name('editcontent');
    Route::post('/marketplace_cmss/banner_image_upload', 'MarketplaceCmsController@banner_image_upload')->name('banner_image_upload');
    Route::post('/marketplace_cmss/updateContent', 'MarketplaceCmsController@updateContent')->name('updateContent');
});

Route::resource('/marketplace_cmss', 'MarketplaceCmsController');