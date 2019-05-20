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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//invoice part
Route::get('invoices', 'InvoiceController@index')->name('invoice-index');
Route::get('invoices/create', 'InvoiceController@create');
Route::get('invoices/duplicate/{invoice}', 'InvoiceController@duplicate');
Route::get('invoices/{invoice}', 'InvoiceController@show');
Route::get('invoices/pdf/{invoice}/{print?}', 'InvoiceController@generatePdf')->where(['id' => '[0-9]+', 'print' => 'print'])->name('generate-pdf');
Route::post('invoices', 'InvoiceController@store');
Route::get('invoices/{invoice}/edit', 'InvoiceController@edit');
Route::patch('invoices/{invoice}', 'InvoiceController@update');
Route::get('invoices/mark-as-paid/{invoice}', 'InvoiceController@markAsPaid')->name('mark-as-paid');
Route::get('invoices/record-payment/{id}', 'InvoiceController@recordPayment')->where(['id' => '[0-9]+'])->name('record-payment');
Route::post('invoices/record-payment/save/{id}', 'InvoiceController@recordPaymentSave')->where(['id' => '[0-9]+'])->name('record-payment-save');
Route::post('invoices/get/date', 'InvoiceController@getDate')->name('get-date-for-filter');
Route::get('invoices/update/status', 'InvoiceController@changeInvoicesStatus')->name('update-status');


//Invoice items part
Route::get('items/select-items', 'InvoiceItemController@selectItems');
Route::get('items', 'InvoiceItemController@index');
Route::post('items', 'InvoiceItemController@store');
Route::post('items/{item}', 'InvoiceItemController@update');
Route::delete('items/destroy/{item}', 'InvoiceItemController@destroy');

//invoice email part
Route::get('invoice-mail/create/{invoice}', 'InvoiceMailController@create');
Route::post('invoice-mail/{invoice}', 'InvoiceMailController@store');

//customers part
Route::get('customers', 'CustomerController@index');
Route::get('customers/create', 'CustomerController@create');
Route::post('customers', 'CustomerController@store');
Route::post('customers/{customer}', 'CustomerController@update');
Route::get('customers/{customer}/edit', 'CustomerController@edit');
Route::patch('customers/{customer}', 'CustomerController@update');
Route::delete('customers/{customer}', 'CustomerController@destroy');


//companies part
Route::prefix('company')->group(function () {
    Route::get('', 'CompanyController@index')->name('company-list');
    Route::get('create', 'CompanyController@create')->name('company-create');
    Route::get('update/{id}', 'CompanyController@update')->name('company-update')->where(['id' => '[0-9]+']);
    Route::post('create/save', 'CompanyController@createSave')->name('company-create-save');
    Route::post('upload/save/{id}', 'CompanyController@updateSave')->name('company-upload-save')->where(['id' => '[0-9]+']);
    Route::get('{id}', 'CompanyController@deleteImage')->name('company-image-delete')->where(['id' => '[0-9]+']);
});

//users path
Route::prefix('user')->middleware('admin')->group(function () {
    Route::get('', 'UserController@index')->name('users-list');
    Route::get('create/{id?}', 'UserController@create')->name('users-create')->where(['id' => '[0-9]+']);
    Route::post('create/save', 'UserController@createSave')->name('users-create-save');
    Route::post('update/save/{id}', 'UserController@updateSave')->name('users-update-save')->where(['id' => '[0-9]+']);
    Route::delete('{id}', 'UserController@destroy')->name('user-destroy')->where(['id' => '[0-9]+']);
});

//counter
Route::patch('counters/{invoiceNumber}', 'CounterController@update');
Route::get('counters', 'CounterController@index');
