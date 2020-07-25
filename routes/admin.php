<?php

use Illuminate\Support\Facades\Route;

define('PAGINATION_COUNT', 10);

Route::group(['middleware' => 'guest:admin', 'namespace' => 'admin'], function () {
    Route::get('login', 'LoginController@getLogin')->name('get.admin.login');
    Route::post('login', 'LoginController@login')->name('admin.login');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
});

Route::group(['middleware' => 'auth:admin', 'namespace' => 'admin'], function () {

    Route::get('/', 'DashboardController@index')->name('admin.dashboard');
    ############################ begin Languages routes ######################################
    Route::group(['prefix' => 'languages'], function () {
        Route::get('/', 'LanguagesController@index')->name('admin.languages');
        Route::get('create', 'LanguagesController@create')->name('admin.languages.create');
        Route::post('store', 'LanguagesController@store')->name('admin.languages.store');
        Route::get('edit/{id}', 'LanguagesController@edit')->name('admin.languages.edit');
        Route::post('update/{id}', 'LanguagesController@update')->name('admin.languages.update');
        Route::get('delete/{id}', 'LanguagesController@destroy')->name('admin.languages.delete');
    });
    ############################ end Languages routes #######################################

    ############################ begin MainCategories routes ######################################
    Route::group(['prefix' => 'main_categories'], function () {
        Route::get('/', 'MainCategoriesController@index')->name('admin.maincategories');
        Route::get('create', 'MainCategoriesController@create')->name('admin.maincategories.create');
        Route::post('store', 'MainCategoriesController@store')->name('admin.maincategories.store');
        Route::get('edit/{id}', 'MainCategoriesController@edit')->name('admin.maincategories.edit');
        Route::post('update/{id}', 'MainCategoriesController@update')->name('admin.maincategories.update');
        Route::get('delete/{id}', 'MainCategoriesController@destroy')->name('admin.maincategories.delete');
        Route::get('changeStatus/{id}', 'MainCategoriesController@changeStatus')->name('admin.maincategories.status');
    });
    ############################ end MainCategories routes #######################################

    ######################### Begin vendors Routes ###############################################
    Route::group(['prefix' => 'vendors'], function () {
        Route::get('/', 'VendorsController@index')->name('admin.vendors');
        Route::get('create', 'VendorsController@create')->name('admin.vendors.create');
        Route::post('store', 'VendorsController@store')->name('admin.vendors.store');
        Route::get('edit/{id}', 'VendorsController@edit')->name('admin.vendors.edit');
        Route::post('update/{id}', 'VendorsController@update')->name('admin.vendors.update');
        Route::get('delete/{id}', 'VendorsController@destroy')->name('admin.vendors.delete');
        Route::get('changeStatus/{id}', 'VendorsController@changeStatus')->name('admin.vendors.status');

    });
    ######################### End  vendors Routes  ##############################################

});

