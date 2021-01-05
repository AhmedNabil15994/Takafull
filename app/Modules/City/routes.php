<?php

/*----------------------------------------------------------
Cities
----------------------------------------------------------*/
Route::group(['prefix' => '/cities'] , function () {
    Route::get('/', 'CityControllers@index');
    Route::get('/add', 'CityControllers@add');
    Route::get('/arrange', 'CityControllers@arrange');
    Route::get('/charts', 'CityControllers@charts');
    Route::get('/edit/{id}', 'CityControllers@edit');
    Route::post('/update/{id}', 'CityControllers@update');
    Route::post('/fastEdit', 'CityControllers@fastEdit');
	Route::post('/create', 'CityControllers@create');
    Route::get('/delete/{id}', 'CityControllers@delete');
    Route::post('/arrange/sort', 'CityControllers@sort');
});