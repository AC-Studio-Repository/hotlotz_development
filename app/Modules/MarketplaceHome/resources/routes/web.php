<?php

Route::name('marketplace_homes.')->group(function () {
	Route::get('/marketplace_homes/substainable_banner_index', 'MarketplaceHomeController@substainable_banner_index')->name('substainable_banner_index');
    Route::post('/marketplace_homes/sustainable_sorcing_banner_upload', 'MarketplaceHomeController@sustainable_sorcing_banner_upload')->name('sustainable_sorcing_banner_upload');
    Route::post('/marketplace_homes/store_substainable_Info', 'MarketplaceHomeController@store_substainable_Info')->name('store_substainable_Info');

    
    Route::get('/marketplace_homes/collaboration_banner_index', 'MarketplaceHomeController@collaboration_banner_index')->name('collaboration_banner_index');
    Route::post('/marketplace_homes/collaboration_banner_upload', 'MarketplaceHomeController@collaboration_banner_upload')->name('collaboration_banner_upload');
    Route::post('/marketplace_homes/store_collaboration_info', 'MarketplaceHomeController@store_collaboration_info')->name('store_collaboration_info');

    Route::get('/marketplace_homes/collaboration_list', 'MarketplaceHomeController@collaboration_list')->name('collaboration_list');
    Route::get('/marketplace_homes/editcontent', 'MarketplaceHomeController@editcontent')->name('editcontent');
    Route::post('/marketplace_homes/updateContent', 'MarketplaceHomeController@updateContent')->name('updateContent');
    Route::post('/marketplace_homes/info_banner_upload', 'MarketplaceHomeController@info_banner_upload')->name('info_banner_upload');

    Route::get('/marketplace_homes/detail_item_policy', 'MarketplaceHomeController@detail_item_policy')->name('itemDetailPolicyCms');
    Route::post('/marketplace_homes/updatePolicyContent', 'MarketplaceHomeController@updatePolicyContent')->name('updatePolicyContent');
});

Route::resource('/marketplace_homes', 'MarketplaceHomeController');