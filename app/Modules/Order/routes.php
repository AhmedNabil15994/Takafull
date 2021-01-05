<?php

/*----------------------------------------------------------
Orders
----------------------------------------------------------*/
Route::group(['prefix' => '/orders'] , function () {
    Route::get('/', 'OrderControllers@index');
    Route::post('/fastEdit', 'OrderControllers@fastEdit');
    Route::post('/delete', 'OrderControllers@softDelete');
    Route::get('/delete/{id}', 'OrderControllers@delete');
    Route::get('/charts', 'OrderControllers@charts');
    Route::post('/changeStatus/{status}', 'OrderControllers@changeStatus');
});

Route::group(['prefix' => '/trashes'] , function () {
    Route::get('/', 'OrderControllers@trashes');
    Route::post('/fastEdit', 'OrderControllers@fastEdit');
    Route::post('/delete', 'OrderControllers@softDelete');
    Route::get('/delete/{id}', 'OrderControllers@delete');
    Route::get('/charts', 'OrderControllers@charts');
    Route::post('/changeStatus/{status}', 'OrderControllers@changeStatus');
});

Route::group(['prefix' => '/newOrders'] , function () {
    Route::get('/', 'OrderControllers@newOrders');
    Route::post('/fastEdit', 'OrderControllers@fastEdit');
    Route::post('/delete', 'OrderControllers@softDelete');
    Route::get('/delete/{id}', 'OrderControllers@delete');
    Route::get('/charts', 'OrderControllers@charts');
    Route::post('/changeStatus/{status}', 'OrderControllers@changeStatus');
});

Route::group(['prefix' => '/sentOrders'] , function () {
    Route::get('/', 'OrderControllers@sentOrders');
    Route::post('/fastEdit', 'OrderControllers@fastEdit');
    Route::post('/delete', 'OrderControllers@softDelete');
    Route::get('/delete/{id}', 'OrderControllers@delete');
    Route::get('/charts', 'OrderControllers@charts');
    Route::post('/changeStatus/{status}', 'OrderControllers@changeStatus');
});

Route::group(['prefix' => '/delayedOrders'] , function () {
    Route::get('/', 'OrderControllers@delayedOrders');
    Route::post('/fastEdit', 'OrderControllers@fastEdit');
    Route::post('/delete', 'OrderControllers@softDelete');
    Route::get('/delete/{id}', 'OrderControllers@delete');
    Route::get('/charts', 'OrderControllers@charts');
    Route::post('/changeStatus/{status}', 'OrderControllers@changeStatus');
});

Route::group(['prefix' => '/receivedOrders'] , function () {
    Route::get('/', 'OrderControllers@receivedOrders');
    Route::post('/fastEdit', 'OrderControllers@fastEdit');
    Route::post('/delete', 'OrderControllers@softDelete');
    Route::get('/delete/{id}', 'OrderControllers@delete');
    Route::get('/charts', 'OrderControllers@charts');
    Route::post('/changeStatus/{status}', 'OrderControllers@changeStatus');
});

Route::group(['prefix' => '/unRepliedOrders'] , function () {
    Route::get('/', 'OrderControllers@unRepliedOrders');
    Route::post('/fastEdit', 'OrderControllers@fastEdit');
    Route::post('/delete', 'OrderControllers@softDelete');
    Route::get('/delete/{id}', 'OrderControllers@delete');
    Route::get('/charts', 'OrderControllers@charts');
    Route::post('/changeStatus/{status}', 'OrderControllers@changeStatus');
});

Route::group(['prefix' => '/cancelledOrders'] , function () {
    Route::get('/', 'OrderControllers@cancelledOrders');
    Route::post('/fastEdit', 'OrderControllers@fastEdit');
    Route::post('/delete', 'OrderControllers@softDelete');
    Route::get('/delete/{id}', 'OrderControllers@delete');
    Route::get('/charts', 'OrderControllers@charts');
    Route::post('/changeStatus/{status}', 'OrderControllers@changeStatus');
});
