<?php

/*----------------------------------------------------------
User Auth
----------------------------------------------------------*/
Route::group(['prefix' => '/backend'] , function () {
    Route::get('/login', 'AuthControllers@login')->name('login');
    Route::post('/login', 'AuthControllers@doLogin')->name('doLogin');
    Route::get('/logout', 'AuthControllers@logout');

    Route::post('/resetPassword', 'AuthControllers@resetPassword')->name('resetPassword');
    Route::get('/changePassword/{id}', 'AuthControllers@changePassword')->name('changePassword');
    Route::post('/changePassword/{id}', 'AuthControllers@completeReset')->name('completeReset');

});