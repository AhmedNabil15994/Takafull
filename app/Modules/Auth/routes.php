<?php

/*----------------------------------------------------------
User Auth
----------------------------------------------------------*/
Route::group(['prefix' => '/backend'] , function () {
    Route::get('/login', 'AuthControllers@login')->name('login');
    Route::post('/login', 'AuthControllers@doLogin')->name('doLogin');
    Route::get('/logout', 'AuthControllers@logout');
});