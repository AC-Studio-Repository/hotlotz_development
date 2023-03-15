<?php

Route::name('internal_advert.')->group(function () {

    Route::get('/internal_advert/{id}/detail', 'InternalAdvertController@detail')->name('detail');

    Route::post('/internal_advert/{id}/edit', 'InternalAdvertController@update')->name('edit');

    Route::post('/internal_advert/store', 'InternalAdvertController@update')->name('create');
});

Route::resource('/internal_advert', 'InternalAdvertController');

