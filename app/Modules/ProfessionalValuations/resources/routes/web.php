<?php

Route::name('professional_valuations.')->group(function () {
    Route::get('/professional_valuations/content_index', 'ProfessionalValuationsController@contentIndex')->name('contentIndex');
    Route::get('/professional_valuations/editcontent', 'ProfessionalValuationsController@editcontent')->name('editcontent');
    Route::post('/professional_valuations/banner_image_upload', 'ProfessionalValuationsController@banner_image_upload')->name('banner_image_upload');
    Route::post('/professional_valuations/key_contact_image_upload', 'ProfessionalValuationsController@key_contact_image_upload')->name('key_contact_image_upload');
    Route::post('/professional_valuations/updateContent', 'ProfessionalValuationsController@updateContent')->name('updateContent');
});

Route::resource('/professional_valuations', 'ProfessionalValuationsController');