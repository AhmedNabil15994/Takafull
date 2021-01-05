<?php

/*----------------------------------------------------------
Blocked Users
----------------------------------------------------------*/
Route::group(['prefix' => '/blockedUsers'] , function () {
    Route::get('/', 'BlockedUsersControllers@index');
    Route::post('/fastEdit', 'BlockedUsersControllers@fastEdit');
    Route::get('/delete/{id}', 'BlockedUsersControllers@delete');
    Route::get('/arrange', 'BlockedUsersControllers@arrange');
    Route::post('/arrange/sort', 'BlockedUsersControllers@sort');
});