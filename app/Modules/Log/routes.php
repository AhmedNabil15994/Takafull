<?php

/*----------------------------------------------------------
Logs
----------------------------------------------------------*/
Route::group(['prefix' => '/logs'] , function () {
    Route::get('/', 'LogControllers@index');
    Route::post('/fastEdit', 'LogControllers@fastEdit');
    Route::get('/delete/{id}', 'LogControllers@delete');
    Route::get('/arrange', 'LogControllers@arrange');
    Route::post('/arrange/sort', 'LogControllers@sort');
});