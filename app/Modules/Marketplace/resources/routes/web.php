<?php

    Route::name('marketplaces.')->group(function () {

        ## New Marketplace Items
        Route::get('/marketplaces/new_additions', 'MarketplaceController@newMarketplaceItems')->name('new_additions');
        Route::post('/marketplaces/new_addition_filter', 'MarketplaceController@newAdditionFilter')->name('new_addition_filter');

        ## Sold Marketplace Items
        Route::get('/marketplaces/sold_items', 'MarketplaceController@soldMarketplaceItems')->name('sold_items');
        Route::post('/marketplaces/sold_item_filter', 'MarketplaceController@soldItemFilter')->name('sold_item_filter');

        ##GenearateLabel PDF for New Addition
        Route::post('/marketplaces/generate-label', 'MarketplaceController@generateLabel')->name('generateLabel');
        ##GenearateBuyerLabel PDF for Sold Items
        Route::post('/marketplaces/generate-buyer-label', 'MarketplaceController@generateBuyerLabel')->name('generateBuyerLabel');

        ## Marketplace All Items
        Route::get('/marketplaces/marketplace_all', 'MarketplaceController@marketplaceAllItems')->name('marketplace_all');
        Route::post('/marketplaces/marketplace_all_filter', 'MarketplaceController@marketplaceAllItemsFilter')->name('marketplace_all_filter');
        ##GenearateLabel PDF for Marketplace All Items
        Route::post('/marketplaces/generate_label_mp_all', 'MarketplaceController@generateLabelMpAll')->name('generateLabelMpAll');
    });

    Route::resource('/marketplaces', 'MarketplaceController');
