<?php

/*----------------------------------------------------------
Files
----------------------------------------------------------*/
Route::group(['prefix' => '/files'] , function () {
    Route::get('/', 'FileControllers@index');
    Route::get('/add', 'FileControllers@add');
    Route::get('/arrange', 'FileControllers@arrange');
    Route::get('/charts', 'FileControllers@charts');
    Route::get('/edit/{id}', 'FileControllers@edit');
    Route::post('/update/{id}', 'FileControllers@update');
    Route::post('/fastEdit', 'FileControllers@fastEdit');
	Route::post('/create', 'FileControllers@create');
    Route::get('/delete/{id}', 'FileControllers@delete');
    Route::post('/arrange/sort', 'FileControllers@sort');

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage', 'FileControllers@uploadImage');
    Route::post('/edit/{id}/editImage', 'FileControllers@uploadImage');
    Route::post('/edit/{id}/deleteImage', 'FileControllers@deleteImage');
});