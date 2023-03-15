<?php

Route::name('strategic_partners.')->group(function () {
    Route::get('/strategic_partners/showlist', 'StrategicPartnerController@showlist')->name('showlist');
    Route::post('/strategic_partners/image_upload', 'StrategicPartnerController@itemImageUpload')->name('strategic_partner_image_upload');

    Route::get('/strategic_partners/infoIndex', 'StrategicPartnerController@infoIndex')->name('infoIndex');
    Route::get('/strategic_partners/editcontent', 'StrategicPartnerController@editcontent')->name('editcontent');
    Route::post('/strategic_partners/banner_image_upload', 'StrategicPartnerController@banner_image_upload')->name('banner_image_upload');
    Route::post('/strategic_partners/updateContent', 'StrategicPartnerController@updateContent')->name('updateContent');
});

Route::resource('/strategic_partners', 'StrategicPartnerController');