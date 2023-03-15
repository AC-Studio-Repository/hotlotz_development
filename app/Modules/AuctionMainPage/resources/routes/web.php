<?php

Route::name('auction_main_pages.')->group(function () {
	Route::get('/auction_main_pages/auctionResultsIndex', 'AuctionMainPageController@auctionResultsIndex')->name('auctionResultsIndex');
    Route::get('/auction_main_pages/editAuctionResultsContent', 'AuctionMainPageController@editAuctionResultsContent')->name('editAuctionResultsContent');
    Route::post('/auction_main_pages/bannerAuctionResultsUpload', 'AuctionMainPageController@bannerAuctionResultsUpload')->name('bannerAuctionResultsUpload');
    Route::post('/auction_main_pages/updateAuctionResultsContent', 'AuctionMainPageController@updateAuctionResultsContent')->name('updateAuctionResultsContent');
    
    Route::get('/auction_main_pages/pastCataloguesIndex', 'AuctionMainPageController@pastCataloguesIndex')->name('pastCataloguesIndex');
    Route::get('/auction_main_pages/editPastCataloguesContent', 'AuctionMainPageController@editPastCataloguesContent')->name('editPastCataloguesContent');
    Route::post('/auction_main_pages/bannerPastCataloguesUpload', 'AuctionMainPageController@bannerPastCataloguesUpload')->name('bannerPastCataloguesUpload');
    Route::post('/auction_main_pages/updatePastCataloguesContent', 'AuctionMainPageController@updatePastCataloguesContent')->name('updatePastCataloguesContent');
});

Route::resource('/auction_main_pages', 'AuctionMainPageController');