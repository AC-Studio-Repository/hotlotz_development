<?php

    Route::name('auctions.')->group(function () {
        Route::post('/auctions/publishAuction', 'AuctionController@publishAuction')->name('publishAuction');

        Route::get('/auctions/{auction}/lot_list', 'AuctionController@lotReorderList')->name('lot_list');
        Route::post('/auctions/{auction}/lot_reordering', 'AuctionController@lotReordering')->name('lot_reordering');
        Route::post('/auctions/{id}/create_lots_into_toolbox', 'AuctionController@createLotsIntoToolbox')->name('create_lots_into_toolbox');
        Route::get('/auctions/{id}/show_no_permission_items', 'AuctionController@showNoPermissionItems')->name('show_no_permission_items');

        Route::post('/auctions/filter', 'AuctionController@filter')->name('filter');

        Route::get('/auctions/{auction}/generate-label', 'AuctionController@generateLabel')->name('generateLabel');
        Route::get('/auctions/{auction}/generate-catelogue', 'AuctionController@generateCatalogue')->name('generateCatelogue');
        Route::get('/auctions/{auction}/generate-buyer-label', 'AuctionController@generateBuyerLabel')->name('generateBuyerLabel');
        Route::get('/auctions/{auction}/generateSaleReport', 'AuctionController@generateSaleReport')->name('generateSaleReport');
        Route::get('/auctions/{auction}/generateKycReport', 'AuctionController@generateKycReport')->name('generateKycReport');

        Route::get('/auctions/search/title', 'AuctionController@search')->name('search');
        Route::get('/auctions/{auction}/getSaleReport', 'AuctionController@getSaleReport')->name('getSaleReport');

        Route::get('/auctions/{auction}/generateSellerReport', 'AuctionController@generateSellerReport')->name('generateSellerReport');

        Route::get('/auctions/{auction}/getPreAuctionItemReport', 'AuctionController@getPreAuctionItemReport')->name('getPreAuctionItemReport');
        Route::get('/auctions/{auction}/getLotList', 'AuctionController@getLotList')->name('getLotList');
        Route::get('/auctions/{auction}/getTotalSettlement', 'AuctionController@getTotalSettlement')->name('getTotalSettlement');
        Route::get('/auctions/{auction}/getWinnerList', 'AuctionController@getWinnerList')->name('getWinnerList');
        Route::get('/auctions/{auction}/getBidderList', 'AuctionController@getBidderList')->name('getBidderList');

        #PublishToFrontend
        Route::post('/auctions/{auction_id}/publish_to_frontend', 'AuctionController@publishToFrontend')->name('publish_to_frontend');
        #UnpublishToFrontend
        Route::post('/auctions/{auction_id}/unpublish_to_frontend', 'AuctionController@unpublishToFrontend')->name('unpublish_to_frontend');
        
        #Show Auction URLs
        Route::get('/auctions/{auction}/show/{tab_name}', 'AuctionController@showAuction')->name('show_auction');

        #Send KYC Seller Emails
        Route::get('/auctions/{auction}/send_kyc_individual_seller_email', 'AuctionController@sendKycIndividualSellerEmails')->name('send_kyc_individual_seller_email');
        Route::get('/auctions/{auction}/send_kyc_company_seller_email', 'AuctionController@sendKycCompanySellerEmails')->name('send_kyc_company_seller_email');

        #Send KYC Byuer Emails
        Route::get('/auctions/{auction}/send_kyc_buyer_email', 'AuctionController@sendKycBuyerEmails')->name('send_kyc_buyer_email');
        
        #Check for Delete
        Route::post('/auctions/{auction}/check_delete', 'AuctionController@checkDelete')->name('check_delete');

        #Lifecycle Reset for All Items
        Route::get('/auctions/{auction}/lifecycle_reset', 'AuctionController@lifecycleResetForAllItems')->name('lifecycle_reset');
    });

    Route::resource('/auctions', 'AuctionController');
