<?php

	Route::name('itemlifecycletriggers.')->group(function () {

		Route::post('/itemlifecycletriggers/lifecycle', 'ItemLifecycleTriggerController@lifecycle')->name('lifecycle');
	});

    Route::resource('/itemlifecycletriggers', 'ItemLifecycleTriggerController');

