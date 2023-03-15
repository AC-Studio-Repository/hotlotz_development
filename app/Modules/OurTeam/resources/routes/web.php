<?php

Route::name('our_teams.')->group(function () {
    Route::get('/our_teams/showlist', 'OurTeamController@showlist')->name('showlist');
    Route::post('/our_teams/imageUpload', 'OurTeamController@imageUpload')->name('imageUpload');

    Route::get('/our_teams/infoIndex', 'OurTeamController@infoIndex')->name('infoIndex');
    Route::get('/our_teams/editcontent', 'OurTeamController@editcontent')->name('editcontent');
    Route::post('/our_teams/banner_image_upload', 'OurTeamController@banner_image_upload')->name('banner_image_upload');
    Route::post('/our_teams/updateContent', 'OurTeamController@updateContent')->name('updateContent');

    Route::post('/our_teams/team_member_reordering', 'OurTeamController@teamMemberReordering')->name('team_member_reordering');
});

Route::resource('/our_teams', 'OurTeamController');