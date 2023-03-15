<?php

	Route::name('categories.')->group(function () {
	    Route::post('/categories/{id}/category_property_update', 'CategoryController@categoryPropertyUpdate')->name('category_property_update');
	    
	    Route::get('/categories/{id}/getSubCategory', 'CategoryController@getSubCategory')->name('getSubCategory');
	});

    Route::resource('/categories', 'CategoryController');

