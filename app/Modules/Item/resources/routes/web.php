<?php
    Route::name('items.')->group(function () {
        ## Item Image
        Route::post('/items/{id}/image_upload', 'ItemController@itemImageUpload')->name('image_upload');
        Route::post('/items/{item_image_id}/image_delete', 'ItemController@itemImageDelete')->name('image_delete');

        ## Item Video
        Route::post('/items/{id}/video_upload', 'ItemController@itemVideoUpload')->name('video_upload');
        Route::post('/items/{item_video_id}/video_delete', 'ItemController@itemVideoDelete')->name('video_delete');

        ## Item Internal Photo
        Route::post('/items/{id}/internal_photo_upload', 'ItemController@itemInternalPhotoUpload')->name('internal_photo_upload');
        Route::post('/items/{item_internal_photo_id}/internal_photo_delete', 'ItemController@itemInternalPhotoDelete')->name('internal_photo_delete');


        Route::post('/items/{id}/seller_update', 'ItemController@itemSellerDetailUpdate')->name('seller_update'); //Seller Details

        Route::post('/items/{id}/sellwithus_update', 'ItemController@itemSellWithUsUpdate')->name('sellwithus_update'); //Sell With Us

        Route::post('/items/{id}/lifecycle_update', 'ItemController@itemLifecycleUpdate')->name('lifecycle_update'); //Lifecycle

        Route::post('/items/{id}/save_item_fee_structure', 'ItemController@saveItemFeeStructure')->name('save_item_fee_structure'); //Fee Structure

        Route::post('/items/{id}/item_purchase', 'ItemController@itemPurchaseDetails')->name('item_purchase'); //Purchase Details

        Route::post('/items/getCategoryProperty', 'ItemController@getCategoryProperty')->name('getCategoryProperty');

        Route::post('/items/filter', 'ItemController@filter')->name('filter');

        Route::post('/items/{item_id}/duplicate', 'ItemController@duplicateItem')->name('duplicate');

        //Generate Item Code
        Route::get('/items/{customer_id}/generateItemCode', 'ItemController@generateItemCode')->name('generateItemCode');

        //Approved Cataloguing
        Route::post('/items/{id}/approvedCataloguing', 'ItemController@approvedCataloguing')->name('approvedCataloguing');
        //Approved Valuation
        Route::post('/items/{id}/approvedValuation', 'ItemController@approvedValuation')->name('approvedValuation');
        //Approved Valuation
        Route::post('/items/{id}/approvedFeeStructure', 'ItemController@approvedFeeStructure')->name('approvedFeeStructure');


        //Declined Item
        Route::post('/items/{id}/decline', 'ItemController@declinedItem')->name('decline');
        //Withdrawn Item
        Route::post('/items/{id}/withdraw', 'ItemController@withdrawnItem')->name('withdraw');
        //Internal Withdrawn Item
        Route::post('/items/{id}/internal_withdraw', 'ItemController@internalWithdrawnItem')->name('internal_withdraw');
        //Dispatched Item
        Route::post('/items/{id}/dispatch', 'ItemController@dispatchedItem')->name('dispatch');
        //Delivery Booked Item
        Route::post('/items/{id}/delivery_book', 'ItemController@deliveryBookedItem')->name('delivery_book');
        //Cancel Sale Item
        Route::post('/items/{id}/cancel_sale', 'ItemController@cancelSaleItem')->name('cancel_sale');
        //Private Sale Item
        Route::post('/items/{id}/private_sale', 'ItemController@privateSaleItem')->name('private_sale');
        //Credit Note Item
        Route::post('/items/{id}/credit_note', 'ItemController@creditNoteItem')->name('credit_note');
        //Cancel Dispatch Item
        Route::post('/items/{id}/cancel_dispatch', 'ItemController@cancelDispatchItem')->name('cancel_dispatch');


        //CheckTab
        Route::post('/items/check_tab', 'ItemController@checkTab')->name('check_tab');

        #Request For Permission
        Route::get('/items/{id}/request_for_permission', 'ItemController@requestForPermission')->name('request_for_permission');

        #addItem from Item
        Route::get('/items/add_item_from_item', 'ItemController@addItemFromItem')->name('add_item_from_item');
        #addItem from Customer
        Route::get('/items/{customer_id}/add_item_from_client', 'ItemController@addItemFromClient')->name('add_item_from_client');
        // #showItem from Customer
        // Route::get('/items/{id}/show_item/{tab_name}', 'ItemController@showItemFromCustomer')->name('show_item');

        # SetHighlight
        Route::post('/items/{id}/set_highlight', 'ItemController@setHighlight')->name('set_highlight');

        Route::get('/items/search', 'ItemController@search')->name('search');

        Route::post('/items/{id}/getRecentlyConsigned', 'ItemController@getRecentlyConsigned')->name('getRecentlyConsigned');

        Route::post('/items/{id}/set_pending_status', 'ItemController@setPendingStatus')->name('set_pending_status');

        Route::get('/items/getimagefullpath/{id}', 'ItemController@getimagefullpath')->name('getimagefullpath');

        #Edit Item URLs
        Route::get('/items/{item}/edit/{tab_name}', 'ItemController@editItem')->name('edit_item');
        #Show Item URLs
        Route::get('/items/{item}/show/{tab_name}', 'ItemController@showItem')->name('show_item');

        Route::post('/items/getConditions', 'ItemController@getConditions')->name('getConditions');

        Route::post('/items/getConditionSolution', 'ItemController@getConditionSolution')->name('getConditionSolution');
        Route::post('/items/{item}/withdraw_fee_setting', 'ItemController@withdrawFeeSetting')->name('withdraw_fee_setting');

    });

    Route::resource('/items', 'ItemController');
