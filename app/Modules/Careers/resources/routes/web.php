<?php

Route::name('careerss.')->group(function () {
    Route::get('/careerss/showlist', 'CareersController@showlist')->name('showlist');
    Route::post('/careerss/documentUpload', 'CareersController@documentUpload')->name('documentUpload');

    Route::get('/careerss/infoIndex', 'CareersController@infoIndex')->name('infoIndex');
    Route::get('/careerss/editcontent', 'CareersController@editcontent')->name('editcontent');
    Route::post('/careerss/banner_image_upload', 'CareersController@banner_image_upload')->name('banner_image_upload');
    Route::post('/careerss/updateContent', 'CareersController@updateContent')->name('updateContent');
});

Route::resource('/careerss', 'CareersController');