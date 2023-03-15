<?php

    Route::resource('/order_summaries', 'OrderSummaryController');

    Route::name('order_summaries.')->group(function () {
        Route::get('/{type}/order-summaries', 'OrderSummaryController@getTypeIndex')->name('getTypeIndex');
        Route::get('/{type}/order-summaries-froms', 'OrderSummaryController@orderFroms')->name('orderFroms');
        Route::get('/{type}/order-summaries-customers', 'OrderSummaryController@orderCustomers')->name('orderCustomers');
    });
