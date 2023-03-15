<?php

Route::name('home_page_random_texts.')->group(function () {
    
});

Route::resource('/home_page_random_texts', 'HomePageRandomTextController');