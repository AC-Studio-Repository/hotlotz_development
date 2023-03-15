<?php
    Route::name('customers.')->group(function () {
        Route::post('/customers/pagination/fetch_data', 'CustomerController@fetch_data')->name('fetch_data');

        Route::get('/customers/select2_all_customer', 'CustomerController@getSelect2CustomerData')->name('select2_all_customer');


        Route::get('/customers/{id}/select2', 'CustomerController@getSelect2CustomerDataById')->name('select2customerbyid');
        
        Route::post('/customers/check_unique_customer_email', 'CustomerController@checkUniqueCustomerEmail')->name('check_unique_customer_email');
        Route::post('/customers/ajaxcreate', 'CustomerController@ajaxCreate')->name('ajaxcreate');
        Route::get('/customers/{id}/get_customer', 'CustomerController@getCustomerById')->name('get_customer');

        Route::post('/customers/{customer_id}/document_upload/{type}', 'CustomerController@customerDocumentUpload')->name('document_upload');
        Route::post('/customers/{customer_document_id}/document_delete', 'CustomerController@customerDocumentDelete')->name('document_delete');

        #createNewInvoice
        Route::post('/customers/{id}/createNewInvoice', 'CustomerController@createNewInvoice')->name('createNewInvoice');
        #createNewInvoicePrivate
        Route::post('/customers/{id}/createNewInvoicePrivate', 'CustomerController@createNewInvoicePrivate')->name('createNewInvoicePrivate');

        #Request For Permission
        Route::get('/customers/{id}/request_for_permission', 'CustomerController@requestForPermission')->name('request_for_permission');

        //CheckTab
        Route::post('/customers/check_tab', 'CustomerController@checkTab')->name('check_tab');

        //Get Address
        Route::post('/customers/get_address', 'CustomerController@getAddress')->name('get_address');
        //Create Address
        Route::post('/customers/address_create', 'CustomerController@addressCreate')->name('address_create');
        //Update Address
        Route::post('/customers/address_update', 'CustomerController@addressUpdate')->name('address_update');
        //Delete Address
        Route::post('/customers/delete_address', 'CustomerController@deleteAddress')->name('delete_address');

        
        //Decline Invoice
        Route::post('/customers/decline_invoice', 'CustomerController@declineInvoice')->name('declineInvoice');

        Route::get('/customers/search', 'CustomerController@search')->name('search');

        Route::get('/customers/{id}/show-tab', 'CustomerController@showCustomerFromTabname')->name('show_tab');

        Route::post('/customers/{customer}/genereate-saleroom-receipt', 'CustomerController@generateSaleroomReceipt')->name('generateSaleroomReceipt');

        Route::post('/customers/{customer}/genereate-saleroom-dispatch', 'CustomerController@generateSaleroomDispatch')->name('generateSaleroomDispatch');

        Route::post('/customers/{customer}/genereate_seller_report', 'CustomerController@generateSellerReport')->name('generateSellerReport');

        Route::post('/customers/{customer}/send-saleroom-receipt', 'CustomerController@sendSaleroomReceipt')->name('sendSaleroomReceipt');

        Route::post('/customers/{customer_id}/sell_item', 'CustomerController@sellItemPaginate')->name('sell_item');

        Route::post('/customers/{customer_id}/purchased_item', 'CustomerController@purchasedItemPaginate')->name('purchased_item');

        Route::post('/customers/{customer}/remote-login', 'CustomerController@remoteLogin')->name('remoteLogin');

        Route::get('/customers/{customer}/split-settlement/{invoice_id}', 'CustomerController@splitSettlement')->name('splitSettlement');

        Route::get('/customers/{customer}/getSettlementList', 'CustomerController@getSettlementList')->name('getSettlementList');
        Route::get('/customers/{customer}/getInvoiceList', 'CustomerController@getInvoiceList')->name('getInvoiceList');
        Route::get('/customers/{customer}/getAdhocInvoiceList', 'CustomerController@getAdhocInvoiceList')->name('getAdhocInvoiceList');
         Route::get('/customers/{customer}/getPrivateInvoiceList', 'CustomerController@getPrivateInvoiceList')->name('getPrivateInvoiceList');

        //Get Note
        Route::post('/customers/get_note', 'CustomerController@getNote')->name('get_note');
        //Create Note
        Route::post('/customers/note_create', 'CustomerController@createNote')->name('note_create');
        //Update Note
        Route::post('/customers/note_update', 'CustomerController@updateNote')->name('note_update');
        //Delete Note
        Route::post('/customers/delete_note', 'CustomerController@deleteNote')->name('delete_note');


        //Request KYC Email
        Route::get('/customers/{id}/kyc_seller_email', 'CustomerController@sendKycSellerEmail')->name('kyc_seller_email');

        #Edit Customer URLs
        Route::get('/customers/{customer}/edit/{tab_name}', 'CustomerController@editCustomer')->name('edit_customer');
        #Show Customer URLs
        Route::get('/customers/{customer}/show/{tab_name}', 'CustomerController@showCustomer')->name('show_customer');

        //Approved KYC
        Route::post('/customers/{id}/kyc_approve', 'CustomerController@approveKyc')->name('kyc_approve');
    });

    Route::resource('/customers', 'CustomerController');
