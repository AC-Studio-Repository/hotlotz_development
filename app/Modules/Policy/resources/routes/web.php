<?php
Route::name('policies.')->group(function () {
	
	Route::post('/policies/{id}/document_upload', 'PolicyController@policyDocumentUpload')->name('document_upload');
	Route::post('/policies/{id}/document_delete', 'PolicyController@policyDocumentDelete')->name('document_delete');
});
Route::resource('/policies', 'PolicyController');