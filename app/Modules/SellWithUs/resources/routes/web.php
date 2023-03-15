<?php

Route::name('sell_with_uss.')->group(function () {
	Route::get('/sell_with_uss/infopage', 'SellWithUsController@infopage')->name('infopage');
	Route::get('/sell_with_uss/editcontent', 'SellWithUsController@editcontent')->name('editcontent');
	Route::post('/sell_with_uss/updateContent', 'SellWithUsController@updateContent')->name('updateContent');
	Route::get('/sell_with_uss/list', 'SellWithUsController@list')->name('list');
    Route::post('/sell_with_uss/banner_image_upload', 'SellWithUsController@banner_image_upload')->name('banner_image_upload');
});
Route::resource('/sell_with_uss', 'SellWithUsController');