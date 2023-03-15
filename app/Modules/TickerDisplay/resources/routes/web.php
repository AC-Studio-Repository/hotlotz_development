<?php

	Route::name('ticker_displays.')->group(function () {	    
	    Route::post('/ticker_displays/ticker_display_reordering', 'TickerDisplayController@tickerDisplayReordering')->name('ticker_display_reordering');
	});

    Route::resource('/ticker_displays', 'TickerDisplayController');

