<?php
Route::get('/','PagesController@homeIndex');

Auth::routes();
Route::group(['middleware' => ['auth']], function () {    
    Route::get('/home', 'HomeController@index')->name('home');                    
    require (__DIR__ . '/rt_users.php');    
});

// For Clear cache
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
    /* php artisan cache:clear
    php artisan route:clear
    php artisan config:clear
    php artisan view:clear */
});