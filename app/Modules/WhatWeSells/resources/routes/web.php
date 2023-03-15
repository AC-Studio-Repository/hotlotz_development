<?php

	Route::name('what_we_sells.')->group(function () {

		Route::get('/what_we_sells/showlist', 'WhatWeSellsController@showList')->name('showlist');

	    Route::post('/what_we_sells/what_we_sell_reordering', 'WhatWeSellsController@whatWeSellReordering')->name('what_we_sell_reordering');


	    Route::get('/what_we_sells/{what_we_sell}/highlight_list', 'WhatWeSellsController@highLightList')->name('highlight_list');
	    Route::post('/what_we_sells/{what_we_sell}/highlight_reordering', 'WhatWeSellsController@highlightReordering')->name('highlight_reordering');

	    Route::get('/what_we_sells/{what_we_sell}/highlight_create', 'WhatWeSellsController@highLightCreate')->name('highlight_create');
	    Route::post('/what_we_sells/{what_we_sell}/highlight_store', 'WhatWeSellsController@highLightStore')->name('highlight_store');

	    Route::get('/what_we_sells/{id}/highlight_edit/{highlight_id}', 'WhatWeSellsController@highLightEdit')->name('highlight_edit');
	    Route::post('/what_we_sells/{id}/highlight_update/{highlight_id}', 'WhatWeSellsController@highLightUpdate')->name('highlight_update');
	});

    Route::resource('/what_we_sells', 'WhatWeSellsController');

