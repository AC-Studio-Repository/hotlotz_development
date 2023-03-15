<?php
    Route::name('reports.')->group(function () {
        Route::get('/reports/unsold_post_auction', 'ReportController@unsoldPostAuction')->name('unsold_post_auction');
        Route::get('/reports/unsold_post_auction_table', 'ReportController@getUnsoldPostAuctionTable')->name('unsold_post_auction_table');

        ##One Tree Planted Report
        Route::get('/reports/one_tree_planted_report', 'ReportController@oneTreePlantedReport')->name('one_tree_planted_report');
        Route::post('/reports/one_tree_planted_filter', 'ReportController@oneTreePlantedFilter')->name('one_tree_planted_filter');

        ##Precious Stone, Precious Metal Report
        Route::get('/reports/pspm_report', 'ReportController@preciousStonePreciousMetalReport')->name('pspm_report');
        Route::post('/reports/pspm_filter', 'ReportController@preciousStonePreciousMetalFilter')->name('pspm_filter');

    });
    Route::resource('/reports', 'ReportController');
