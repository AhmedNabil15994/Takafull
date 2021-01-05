<?php

/*----------------------------------------------------------
Pages
----------------------------------------------------------*/
Route::group(['prefix' => '/pages'] , function () {
    Route::get('/', 'PageControllers@index');
    Route::get('/add', 'PageControllers@add');
    Route::get('/arrange', 'PageControllers@arrange');
    Route::get('/charts', 'PageControllers@charts');
    Route::get('/edit/{id}', 'PageControllers@edit');
    Route::post('/update/{id}', 'PageControllers@update');
    Route::post('/fastEdit', 'PageControllers@fastEdit');
	Route::post('/create', 'PageControllers@create');
    Route::get('/delete/{id}', 'PageControllers@delete');
    Route::post('/arrange/sort', 'PageControllers@sort');

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage', 'PageControllers@uploadImage');
    Route::post('/edit/{id}/editImage', 'PageControllers@uploadImage');
    Route::post('/edit/{id}/deleteImage', 'PageControllers@deleteImage');
});