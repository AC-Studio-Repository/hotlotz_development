<?php

    Route::post('/payment', 'StripeController@loadForm')->name('form');

    Route::get('/payment/success', 'StripeController@success')->name('success');

    Route::get('/payment/cancel', 'StripeController@cancel')->name('cancel');

    Route::post('/checkout', 'StripeController@checkout')->name('checkout');

    Route::post('/charges', 'StripeController@charges')->name('charges');

    Route::post('/delete/card', 'StripeController@deleteCard')->name('delete.card');
