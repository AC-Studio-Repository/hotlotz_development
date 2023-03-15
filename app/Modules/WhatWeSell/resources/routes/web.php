<?php

Route::name('whatwesells.')->group(function () {
    Route::get('/whatwesells/showlist', 'WhatWeSellController@showlist')->name('showlist');
    Route::post('/whatwesells/image_upload', 'WhatWeSellController@imageUpload')->name('whatwesell_image_upload');
    Route::post('/whatwesells/banner_image_upload', 'WhatWeSellController@banner_image_upload')->name('banner_image_upload');
    Route::post('/whatwesells/detail_image_upload', 'WhatWeSellController@detailImageUpload')->name('detail_image_upload');
    Route::post('/whatwesells/key_contact_image_upload', 'WhatWeSellController@key_contact_image_upload')->name('key_contact_image_upload');

    Route::get('/whatwesells/infopage', 'WhatWeSellController@infopage')->name('infopage');
    Route::post('/whatwesells/info_banner_upload', 'WhatWeSellController@info_banner_upload')->name('info_banner_upload');
    Route::post('/whatwesells/storeInfo', 'WhatWeSellController@storeInfo')->name('storeInfo');
});
Route::resource('/whatwesells', 'WhatWeSellController');