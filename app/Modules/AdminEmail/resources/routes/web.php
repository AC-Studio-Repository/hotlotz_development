<?php

    Route::resource('/admin_emails', 'AdminEmailController');

    Route::name('admin_emails.')->group(function () {
		Route::post('/admin_emails/save', 'AdminEmailController@save')->name('save');
	});