<?php
	
	Route::name('documents.')->group(function () {

        Route::get('/documents/{type}', 'DocumentModuleController@getDocuments')->name('get_documents');

        Route::get('/documents/create/{type}', 'DocumentModuleController@createDocument')->name('create_document');

        Route::get('/documents/restore/{id}', 'DocumentModuleController@restoreDocument');

    });

  	Route::resource('/documents', 'DocumentModuleController');

?>