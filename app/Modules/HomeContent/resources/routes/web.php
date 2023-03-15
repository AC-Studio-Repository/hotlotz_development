<?php

Route::name('home_contents.')->group(function () {
    Route::get('/home_contents/editcontent', 'HomeContentController@editcontent')->name('editcontent');
    Route::post('/home_contents/banner_image_upload', 'HomeContentController@banner_image_upload')->name('banner_image_upload');
    Route::post('/home_contents/updateContent', 'HomeContentController@updateContent')->name('updateContent');
});

Route::resource('/home_contents', 'HomeContentController');