<?php 

/*----------------------------------------------------------
Users
----------------------------------------------------------*/
Route::group(['prefix' => '/users'] , function () {
    Route::get('/', 'UsersControllers@index');
    Route::get('/add', 'UsersControllers@add');
    Route::get('/arrange', 'UsersControllers@arrange');
    Route::post('/arrange/sort', 'UsersControllers@sort');
    Route::get('/charts', 'UsersControllers@charts');
    Route::get('/edit/{id}', 'UsersControllers@edit');
    Route::post('/update/{id}', 'UsersControllers@update');
    Route::post('/fastEdit', 'UsersControllers@fastEdit');
	Route::post('/create', 'UsersControllers@create');
    Route::get('/delete/{id}', 'UsersControllers@delete');

    /*----------------------------------------------------------
    Images
    ----------------------------------------------------------*/

    Route::post('/add/uploadImage', 'UsersControllers@uploadImage');
    Route::post('/edit/{id}/editImage', 'UsersControllers@uploadImage');
    Route::post('/edit/{id}/deleteImage', 'UsersControllers@deleteImage');
});