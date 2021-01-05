<?php

/*----------------------------------------------------------
Photos
----------------------------------------------------------*/
Route::group(['prefix' => '/photos'] , function () {
    Route::get('/', 'PhotoControllers@index');
    Route::get('/add', 'PhotoControllers@add');
    Route::get('/arrange', 'PhotoControllers@arrange');
    Route::get('/charts', 'PhotoControllers@charts');
    Route::get('/edit/{id}', 'PhotoControllers@edit');
    Route::post('/update/{id}', 'PhotoControllers@update');
    Route::post('/fastEdit', 'PhotoControllers@fastEdit');
	Route::post('/create', 'PhotoControllers@create');
    Route::get('/delete/{id}', 'PhotoControllers@delete');
    Route::post('/arrange/sort', 'PhotoControllers@sort');

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage', 'PhotoControllers@uploadImage');
    Route::post('/edit/{id}/editImage', 'PhotoControllers@uploadImage');
    Route::post('/edit/{id}/deleteImage', 'PhotoControllers@deleteImage');
});