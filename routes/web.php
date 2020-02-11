<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

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
    Route::get('/homepage/{alias}/show/{id}', 'Admin\HomepageController@show')->name('homepage.show');
    Route::post('/homepage/{alias}', 'Admin\HomepageController@store')->name('homepage.store');
    Route::post('/homepage/{alias}/update', 'Admin\HomepageController@update')->name('homepage.update');
    Route::post('/homepage/{alias}/delete', 'Admin\HomepageController@destroy')->name('homepage.destroy');

    Route::get('/contact-us/{alias}', 'Admin\DashboardController@contact')->name('contact-us');
    Route::get('/content/{alias}', 'Admin\DashboardController@content')->name('content');
    Route::get('/inbox', 'Admin\DashboardController@inbox')->name('inbox');


    //datatables
    Route::get('/homepage/{alias}/getdata', 'Admin\HomepageController@getData')->name('homepage.getdata'); 
});