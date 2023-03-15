<?php

Route::name('how_to_buys.')->group(function () {
    Route::get('/how_to_buys/editcontent', 'HowToBuyController@editcontent')->name('editcontent');
    Route::post('/how_to_buys/banner_image_upload', 'HowToBuyController@banner_image_upload')->name('banner_image_upload');
    Route::post('/how_to_buys/file_upload', 'HowToBuyController@file_upload')->name('file_upload');
    Route::post('/how_to_buys/updateContent', 'HowToBuyController@updateContent')->name('updateContent');
});

Route::resource('/how_to_buys', 'HowToBuyController');