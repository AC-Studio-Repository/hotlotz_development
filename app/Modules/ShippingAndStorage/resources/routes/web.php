<?php

Route::name('shipping_and_storages.')->group(function () {
    Route::get('/shipping_and_storages/editcontent', 'ShippingAndStorageController@editcontent')->name('editcontent');
    Route::post('/shipping_and_storages/banner_image_upload', 'ShippingAndStorageController@banner_image_upload')->name('banner_image_upload');
    Route::post('/shipping_and_storages/updateContent', 'ShippingAndStorageController@updateContent')->name('updateContent');
});

Route::resource('/shipping_and_storages', 'ShippingAndStorageController');