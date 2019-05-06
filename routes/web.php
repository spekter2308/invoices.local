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
Route::get('company/update/{id}', 'CompanyController@update')->name('company-update');
Route::post('company/create/save', 'CompanyController@createSave')->name('company-create-save');
Route::post('company/upload/save/{id}', 'CompanyController@updateSave')->name('company-upload-save');
