<?php

Route::name('case_study.')->group(function () {

    Route::get('/case_study/{id}/detail', 'CaseStudyController@detail')->name('detail');

    Route::post('/case_study/{id}/edit', 'CaseStudyController@update')->name('edit');

    Route::post('/case_study/store', 'CaseStudyController@update')->name('create');
});

Route::resource('/case_study', 'CaseStudyController');

