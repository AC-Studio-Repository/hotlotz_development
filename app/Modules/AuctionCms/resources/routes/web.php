<?php

Route::name('auction_cmss.')->group(function () {
    Route::get('/auction_cmss/editcontent', 'AuctionCmsController@editcontent')->name('editcontent');
    Route::post('/auction_cmss/banner_image_upload', 'AuctionCmsController@banner_image_upload')->name('banner_image_upload');
    Route::post('/auction_cmss/updateContent', 'AuctionCmsController@updateContent')->name('updateContent');
});

Route::resource('/auction_cmss', 'AuctionCmsController');