<?php

/*----------------------------------------------------------
Our Advantages
----------------------------------------------------------*/
Route::group(['prefix' => '/ourAdvantages'] , function () {
    Route::get('/', 'AdvantageControllers@index');
    Route::get('/add', 'AdvantageControllers@add');
    Route::get('/arrange', 'AdvantageControllers@arrange');
    Route::get('/charts', 'AdvantageControllers@charts');
    Route::get('/edit/{id}', 'AdvantageControllers@edit');
    Route::post('/update/{id}', 'AdvantageControllers@update');
    Route::post('/fastEdit', 'AdvantageControllers@fastEdit');
	Route::post('/create', 'AdvantageControllers@create');
    Route::get('/delete/{id}', 'AdvantageControllers@delete');
    Route::post('/arrange/sort', 'AdvantageControllers@sort');
});