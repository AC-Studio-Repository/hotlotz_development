<?php

Route::name('how_to_sells.')->group(function () {
    Route::get('/how_to_sells/editcontent', 'HowToSellController@editcontent')->name('editcontent');
    Route::post('/how_to_sells/banner_image_upload', 'HowToSellController@banner_image_upload')->name('banner_image_upload');
    Route::post('/how_to_sells/updateContent', 'HowToSellController@updateContent')->name('updateContent');
});

Route::resource('/how_to_sells', 'HowToSellController');