<?php

/*----------------------------------------------------------
Sliders
----------------------------------------------------------*/
Route::group(['prefix' => '/sliders'] , function () {
    Route::get('/', 'SliderControllers@index');
    Route::get('/add', 'SliderControllers@add');
    Route::get('/arrange', 'SliderControllers@arrange');
    Route::get('/charts', 'SliderControllers@charts');
    Route::get('/edit/{id}', 'SliderControllers@edit');
    Route::post('/update/{id}', 'SliderControllers@update');
    Route::post('/fastEdit', 'SliderControllers@fastEdit');
	Route::post('/create', 'SliderControllers@create');
    Route::get('/delete/{id}', 'SliderControllers@delete');
    Route::post('/arrange/sort', 'SliderControllers@sort');

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage', 'SliderControllers@uploadImage');
    Route::post('/edit/{id}/editImage', 'SliderControllers@uploadImage');
    Route::post('/edit/{id}/deleteImage', 'SliderControllers@deleteImage');
});