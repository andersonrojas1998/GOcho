<?php

Route::resource('User', 'UserController');

Route::group(['prefix' => 'users'], function(){
    Route::get('listData', 'UserController@listData');     
    Route::get('delete/{id}', 'UserController@destroy');     
    Route::post('update', 'UserController@update');
    Route::post('create', 'UserController@create');
});
Route::resource('task', 'taskController');

Route::group(['prefix' => 'task'], function(){
    Route::get('listData/{all}/{st}', 'taskController@listData');
    Route::post('crear', 'taskController@create');
    Route::get('delete/{id}', 'taskController@destroy');     
    Route::post('update', 'taskController@update');
    
});
