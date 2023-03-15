<?php

Route::name('hotlotz_concierges.')->group(function () {
    Route::get('/hotlotz_concierges/editcontent', 'HotlotzConciergeController@editcontent')->name('editcontent');
    Route::post('/hotlotz_concierges/banner_image_upload', 'HotlotzConciergeController@banner_image_upload')->name('banner_image_upload');
    Route::post('/hotlotz_concierges/updateContent', 'HotlotzConciergeController@updateContent')->name('updateContent');
});

Route::resource('/hotlotz_concierges', 'HotlotzConciergeController');