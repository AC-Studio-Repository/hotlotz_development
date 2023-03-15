<?php

Route::name('termsandconditions.')->group(function () {
    Route::get('/termsandconditions/showTandC', 'ContentManagementController@displayContent')->name('displayContentTandC');
    Route::get('/termsandconditions/editcontent', 'ContentManagementController@editcontent')->name('editcontent');
    Route::post('/termsandconditions/ajaxRequest', 'ContentManagementController@ajaxRequestPost')->name('ajaxRequestTandC');
    Route::post('/termsandconditions/updateContent', 'ContentManagementController@updateContent')->name('updateContent');

    Route::post('/termsandconditions/{id}/document_upload', 'ContentManagementController@documentUpload')->name('document_upload');
    Route::post('/termsandconditions/{id}/document_delete', 'ContentManagementController@documentDelete')->name('document_delete');
});

Route::resource('/termsandconditions', 'ContentManagementController');