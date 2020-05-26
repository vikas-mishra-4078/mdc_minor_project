<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\LoginController@admin_login')->name('admin-login');

Route::get('admin', 'Auth\LoginController@admin_login')->name('admin-login');

Route::get('logout', function () {
    Auth::logout();
    return redirect('/admin');
});

Auth::routes();

Route::get('dashboard', 'DashboardController@index')->name('dashboard');


// Users
Route::get('customers', 'CustomersController@index')->name('customers-index');
Route::get('customers/add', 'CustomersController@add')->name('customer-add');
Route::post('customers/create', 'CustomersController@create')->name('customer-create');
Route::get('customers/view/{id}', 'CustomersController@view')->name('customer-view');
Route::get('customers/edit/{id}', 'CustomersController@edit')->name('customer-edit');
Route::post('customers/update', 'CustomersController@update')->name('customer-update');
Route::post('customers/status', 'CustomersController@status')->name('customer-status');
Route::post('customers/delete', 'CustomersController@delete')->name('customer-delete');
Route::get('customers/change-password/{id}', 'CustomersController@changePassword')->name('customer-change-password');
Route::post('customers/update-password', 'CustomersController@updatePassword')->name('customer-update-password');
Route::post('customers/verify', 'CustomersController@verify')->name('customer-verify');


// Settings 
Route::get('settings/profile', 'SettingsController@profile')->name('setting-profile');
Route::post('settings/update-profile', 'SettingsController@updateProfile')->name('setting-update-profile');
Route::get('settings/change-password', 'SettingsController@changePassword')->name('setting-change-password');
Route::post('settings/update-password', 'SettingsController@updatePassword')->name('setting-update-password');


// Reviews 
Route::get('reviews', 'ReviewsController@index')->name('reviews-index');
Route::get('reviews/view/{id}', 'ReviewsController@view')->name('review-view');
Route::post('reviews/status', 'ReviewsController@status')->name('review-status');
Route::post('reviews/delete', 'ReviewsController@delete')->name('review-delete');

// User Docs
Route::get('user_docs/{user_id}', 'UserDocsController@index')->name('user-docs-index');
Route::get('user_docs/add/{user_id}', 'UserDocsController@add')->name('user-doc-add');
Route::post('user_docs/create', 'UserDocsController@create')->name('user-doc-create');
Route::get('user_docs/view/{user_id}/{id}', 'UserDocsController@view')->name('user-doc-view');
Route::post('user_docs/delete', 'UserDocsController@delete')->name('user-doc-delete');