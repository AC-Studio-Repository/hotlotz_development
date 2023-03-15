<?php

Route::name('automate_items.')->group(function () {

    Route::post('/automate_items/autocreate', 'AutomateItemController@autoCreate')->name('autocreate');
});

Route::resource('/automate_items', 'AutomateItemController');

