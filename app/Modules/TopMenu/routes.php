<?php

/*----------------------------------------------------------
Top Menu
----------------------------------------------------------*/
Route::group(['prefix' => '/topMenu'] , function () {
    Route::get('/', 'TopMenuControllers@index');
    Route::get('/add', 'TopMenuControllers@add');
    Route::get('/arrange', 'TopMenuControllers@arrange');
    Route::get('/charts', 'TopMenuControllers@charts');
    Route::get('/edit/{id}', 'TopMenuControllers@edit');
    Route::post('/update/{id}', 'TopMenuControllers@update');
    Route::post('/fastEdit', 'TopMenuControllers@fastEdit');
	Route::post('/create', 'TopMenuControllers@create');
    Route::get('/delete/{id}', 'TopMenuControllers@delete');
    Route::post('/arrange/sort', 'TopMenuControllers@sort');
});