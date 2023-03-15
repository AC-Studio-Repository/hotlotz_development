<?php

Route::name('about_uss.')->group(function () {
    Route::get('/about_uss/editcontent', 'AboutUsController@editcontent')->name('editcontent');
    Route::post('/about_uss/banner_image_upload', 'AboutUsController@banner_image_upload')->name('banner_image_upload');
    Route::post('/about_uss/updateContent', 'AboutUsController@updateContent')->name('updateContent');
});

Route::resource('/about_uss', 'AboutUsController');