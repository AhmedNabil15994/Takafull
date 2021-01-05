<?php

/*----------------------------------------------------------
Benefits
----------------------------------------------------------*/
Route::group(['prefix' => '/benefits'] , function () {
    Route::get('/', 'BenefitControllers@index');
    Route::get('/add', 'BenefitControllers@add');
    Route::get('/arrange', 'BenefitControllers@arrange');
    Route::get('/charts', 'BenefitControllers@charts');
    Route::get('/edit/{id}', 'BenefitControllers@edit');
    Route::post('/update/{id}', 'BenefitControllers@update');
    Route::post('/fastEdit', 'BenefitControllers@fastEdit');
	Route::post('/create', 'BenefitControllers@create');
    Route::get('/delete/{id}', 'BenefitControllers@delete');
    Route::post('/arrange/sort', 'BenefitControllers@sort');
});