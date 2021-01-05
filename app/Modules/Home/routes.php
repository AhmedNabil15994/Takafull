<?php

/*----------------------------------------------------------
Home
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/', 'HomeControllers@index');
	Route::post('/language', 'DashboardControllers@changeLang');
});
