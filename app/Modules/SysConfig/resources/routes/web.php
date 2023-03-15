<?php

    Route::resource('/sys_configs', 'SysConfigController');

    Route::name('sys_configs.')->group(function () {
		Route::post('/sys_configs/save', 'SysConfigController@save')->name('save');
	});