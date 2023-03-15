<?php

Route::name('item_duplicator.')->group(function () {

    Route::post('/item_duplicator/duplicate', 'ItemDuplicatorController@duplicate')->name('duplicate');
});

Route::resource('/item_duplicator', 'ItemDuplicatorController');

