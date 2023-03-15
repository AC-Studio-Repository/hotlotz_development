<?php

Route::name('media_resources.')->group(function () {
    Route::get('/media_resources/showlist', 'MediaResourceController@showlist')->name('showlist');
    Route::post('/media_resources/documentUpload', 'MediaResourceController@documentUpload')->name('documentUpload');

    Route::get('/media_resources/infoIndex', 'MediaResourceController@infoIndex')->name('infoIndex');
    Route::get('/media_resources/editcontent', 'MediaResourceController@editcontent')->name('editcontent');
    Route::post('/media_resources/banner_image_upload', 'MediaResourceController@banner_image_upload')->name('banner_image_upload');
    Route::post('/media_resources/asset_file_upload', 'MediaResourceController@asset_file_upload')->name('asset_file_upload');
    Route::post('/media_resources/updateContent', 'MediaResourceController@updateContent')->name('updateContent');
});

Route::resource('/media_resources', 'MediaResourceController');