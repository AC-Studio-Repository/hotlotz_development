<?php

Route::name('location_cmss.')->group(function () {
    Route::get('/location_cmss/editcontent', 'LocationCmsController@editcontent')->name('editcontent');
    Route::post('/location_cmss/banner_image_upload', 'LocationCmsController@banner_image_upload')->name('banner_image_upload');
    Route::post('/location_cmss/updateContent', 'LocationCmsController@updateContent')->name('updateContent');
});

Route::resource('/location_cmss', 'LocationCmsController');