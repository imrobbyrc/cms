<?php

Route::get('/', function () {
    return view('welcome');
});
Route::get('/{alias}', 'FrontEndController@get');

Auth::routes();
Route::get('/admin', 'Admin\DashboardController@index')->name('home');
Route::group(['middleware' => 'auth','prefix' => 'admin'], function() {
    
    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('/role', 'Admin\RoleController')->except([
            'create', 'show', 'edit', 'update'
        ]);

        Route::resource('/users', 'UserController')->except([
            'show'
        ]);
        Route::get('/users/roles/{id}', 'UserController@roles')->name('users.roles');
        Route::put('/users/roles/{id}', 'UserController@setRole')->name('users.set_role');
        Route::post('/users/permission', 'UserController@addPermission')->name('users.add_permission');
        Route::get('/users/role-permission', 'UserController@rolePermission')->name('users.roles_permission');
        Route::put('/users/permission/{role}', 'UserController@setRolePermission')->name('users.setRolePermission');
    });

    Route::get('/home', 'Admin\DashboardController@index')->name('home');
    Route::get('/homepage/{alias}', 'Admin\HomepageController@index')->name('homepage');
    Route::get('/homepage/{alias}/create', 'Admin\HomepageController@create')->name('homepage.create');
    Route::get('/homepage/{alias}/show/{id}', 'Admin\HomepageController@show')->name('homepage.show');
    Route::post('/homepage/{alias}', 'Admin\HomepageController@store')->name('homepage.store');
    Route::get('/homepage/{alias}/edit/{id}', 'Admin\HomepageController@edit')->name('homepage.edit');
    Route::post('/homepage/{alias}/update', 'Admin\HomepageController@update')->name('homepage.update');
    Route::post('/homepage/{alias}/delete', 'Admin\HomepageController@destroy')->name('homepage.destroy');

    Route::get('/content/{alias}', 'Admin\ContentController@index')->name('content');
    Route::get('/content/{alias}/create', 'Admin\ContentController@create')->name('content.create');
    Route::get('/content/{alias}/show/{id}', 'Admin\ContentController@show')->name('content.show');
    Route::post('/content/{alias}', 'Admin\ContentController@store')->name('content.store');
    Route::get('/content/{alias}/edit/{id}', 'Admin\ContentController@edit')->name('content.edit');
    Route::post('/content/{alias}/update', 'Admin\ContentController@update')->name('content.update');
    Route::post('/content/{alias}/delete', 'Admin\ContentController@destroy')->name('content.destroy');

    Route::get('/contact-us/{alias}', 'Admin\ContactController@index')->name('contact-us');
    Route::post('/contact-us/{alias}', 'Admin\ContactController@store')->name('contact-us.store');
    
    Route::get('/inbox', 'Admin\DashboardController@inbox')->name('inbox');
    


    //datatables
    Route::get('/homepage/{alias}/getdata', 'Admin\HomepageController@getData')->name('homepage.getdata');
    Route::get('/content/{alias}/getdata', 'Admin\ContentController@getData')->name('content.getdata');
    Route::post('/ajax_get_all_submenu', 'Admin\DashboardController@ajax_get_all_submenu')->name('ajax_get_all_submenu');
});