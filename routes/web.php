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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//invoice part
Route::get('invoices', 'InvoiceController@index');
Route::get('invoices/create', 'InvoiceController@create');
Route::get('invoices/select-item', 'InvoiceController@selectItem')->name('select-item');
Route::get('invoices/get/select-item', 'InvoiceController@getSelectItem')->name('get-select-item');
Route::get('invoices/create/select-item/{id?}', 'InvoiceController@createSelectItem')->name('create-select-item')->where(['id' => '[0-9]+']);
Route::post('invoices/save/select-item/{id?}', 'InvoiceController@saveSelectItem')->name('save-select-item')->where(['id' => '[0-9]+']);
Route::delete('invoices/delete/select-item/{id}', 'InvoiceController@deleteSelectItem')->name('delete-select-item')->where(['id' => '[0-9]+']);

Route::post('invoices', 'InvoiceController@store');

//customers part
Route::get('customers', 'CustomerController@index');
Route::get('customers/create', 'CustomerController@create');
Route::post('customers', 'CustomerController@store');
Route::post('customers/{customer}', 'CustomerController@update');
Route::get('customers/{customer}/edit', 'CustomerController@edit');
Route::patch('customers/{customer}', 'CustomerController@update');
Route::delete('customers/{customer}', 'CustomerController@destroy');

//companies part
Route::get('company', 'CompanyController@index')->name('company-list');
Route::get('company/create', 'CompanyController@create')->name('company-create');
Route::get('company/update/{id}', 'CompanyController@update')->name('company-update')->where(['id' => '[0-9]+']);
Route::post('company/create/save', 'CompanyController@createSave')->name('company-create-save');
Route::post('company/upload/save/{id}', 'CompanyController@updateSave')->name('company-upload-save')->where(['id' => '[0-9]+']);
