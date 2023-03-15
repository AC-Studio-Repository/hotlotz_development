<?php

Route::name('private_collectionss.')->group(function () {
    Route::get('/private_collectionss/editcontent', 'PrivateCollectionsController@editcontent')->name('editcontent');
    Route::post('/private_collectionss/banner_image_upload', 'PrivateCollectionsController@banner_image_upload')->name('banner_image_upload');
    Route::post('/private_collectionss/updateContent', 'PrivateCollectionsController@updateContent')->name('updateContent');
});

Route::resource('/private_collectionss', 'PrivateCollectionsController');