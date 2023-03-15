<?php

Route::name('glossarys.')->group(function () {
    Route::get('/glossarys/infopage', 'GlossaryController@infopage')->name('infopage');
    Route::get('/glossarys/listpage', 'GlossaryController@listpage')->name('list');
    Route::get('/glossarys/editcontent', 'GlossaryController@editcontent')->name('editcontent');
    Route::post('/glossarys/banner_image_upload', 'GlossaryController@banner_image_upload')->name('banner_image_upload');
    Route::post('/glossarys/updateContent', 'GlossaryController@updateContent')->name('updateContent');
});
Route::resource('/glossarys', 'GlossaryController');