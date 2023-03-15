<?php

	Route::name('emailtriggers.')->group(function () {
		Route::post('/emailtriggers/sendEmailEventAjax', 'EmailTriggerController@sendEmailEventAjax')->name('sendEmailEventAjax');
	});

    Route::resource('/emailtriggers', 'EmailTriggerController');

