<?php

/*----------------------------------------------------------
Bottom Menu
----------------------------------------------------------*/
Route::group(['prefix' => '/bottomMenu'] , function () {
    Route::get('/', 'BottomMenuControllers@index');
    Route::get('/add', 'BottomMenuControllers@add');
    Route::get('/arrange', 'BottomMenuControllers@arrange');
    Route::get('/charts', 'BottomMenuControllers@charts');
    Route::get('/edit/{id}', 'BottomMenuControllers@edit');
    Route::post('/update/{id}', 'BottomMenuControllers@update');
    Route::post('/fastEdit', 'BottomMenuControllers@fastEdit');
	Route::post('/create', 'BottomMenuControllers@create');
    Route::get('/delete/{id}', 'BottomMenuControllers@delete');
    Route::post('/arrange/sort', 'BottomMenuControllers@sort');
});