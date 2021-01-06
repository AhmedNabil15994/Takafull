<?php

/*----------------------------------------------------------
Home
----------------------------------------------------------*/
Route::group(['prefix' => '/'] , function () {
    Route::get('/', 'HomeControllers@index');
	Route::post('/contactUs', 'HomeControllers@contactUs');
	Route::post('/postOrder', 'HomeControllers@postOrder');
});
