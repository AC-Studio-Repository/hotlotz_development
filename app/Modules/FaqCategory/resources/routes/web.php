<?php

Route::name('faqcategories.')->group(function () {
    Route::get('/faqcategories/showlist', 'FaqCategoryController@showlist')->name('showlist');
    Route::get('/faqcategories/categorylist', 'FaqCategoryController@categorylist')->name('faqCategoryList');

    Route::get('/faqcategories/bloglist', 'FaqCategoryController@blogList')->name('bloglist');
});

Route::resource('/faqcategories', 'FaqCategoryController');