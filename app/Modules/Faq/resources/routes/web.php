<?php

Route::name('faqs.')->group(function () {
    Route::get('/faqs/infoIndex', 'FaqController@infoIndex')->name('infoIndex');
    Route::get('/faqs/editcontent', 'FaqController@editcontent')->name('editcontent');
    Route::post('/faqs/banner_image_upload', 'FaqController@banner_image_upload')->name('banner_image_upload');  
    Route::post('/faqs/updateContent', 'FaqController@updateContent')->name('updateContent');
});
Route::resource('/faqs', 'FaqController');