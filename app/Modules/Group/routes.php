<?php 

/*----------------------------------------------------------
Groups
----------------------------------------------------------*/
Route::group(['prefix' => '/groups'] , function () {
    Route::get('/', 'GroupsControllers@index');
    Route::get('/add', 'GroupsControllers@add');
    Route::get('/arrange', 'GroupsControllers@arrange');
    Route::get('/charts', 'GroupsControllers@charts');
    Route::get('/edit/{id}', 'GroupsControllers@edit');
    Route::post('/update/{id}', 'GroupsControllers@update');
    Route::post('/fastEdit', 'GroupsControllers@fastEdit');
	Route::post('/create', 'GroupsControllers@create');
    Route::get('/delete/{id}', 'GroupsControllers@delete');
    Route::post('/arrange/sort', 'GroupsControllers@sort');
});