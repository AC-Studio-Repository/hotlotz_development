<?php

    Route::get('/support', 'SupportController@main')->name('index');

    Route::post('/customer/verify', 'SupportController@verifyCustomerEmail')->name('verify_customer_email');

