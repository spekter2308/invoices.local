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
    return redirect('/invoices');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//invoice part
Route::get('invoices', function () { return view('layouts.app'); })->middleware('auth');
Route::get('api/invoices', 'InvoiceController@index')->name('invoice-index');
Route::get('api/invoices/search', 'InvoiceController@search');
Route::post('invoices/setPageCount', 'InvoiceController@setPageCount');
Route::get('invoices/create', 'InvoiceController@create');
Route::get('invoices/duplicate/{invoice}', 'InvoiceController@duplicate');
Route::get('invoices/{invoice}', 'InvoiceController@show');
Route::post('invoices', 'InvoiceController@store');
Route::get('invoices/{invoice}/edit', 'InvoiceController@edit');
Route::patch('invoices/{invoice}', 'InvoiceController@update');
Route::post('invoices/multiDelete', 'InvoiceController@multiDelete');
Route::get('invoices/destroy/{id}', 'InvoiceController@destroy');
Route::get('invoices/pdf/{invoice}/{print?}', 'InvoiceController@generatePdf')->where(['id' => '[0-9]+', 'print' => 'print'])->name('generate-pdf');
Route::get('invoices/mark-as-paid/{invoice}', 'InvoiceController@markAsPaid')->name('mark-as-paid');
Route::get('invoices/mark-as-unpaid/{invoice}', 'InvoiceController@markAsUnpaid');
Route::get('invoices/record-payment/{id}', 'InvoiceController@recordPayment')->where(['id' => '[0-9]+'])->name('record-payment');
Route::post('invoices/record-payment/save/{id}', 'InvoiceController@recordPaymentSave');
Route::post('invoices/get/date', 'InvoiceController@getDate')->name('get-date-for-filter');
Route::post('invoices/multi-update/status', 'InvoiceController@multiChangeInvoicesStatus')->name('update-status');
Route::get('invoices/unit-update/status/{id}', 'InvoiceController@unitChangeInvoiceStatus');

//Show invoice for customer
Route::get('invoice/invoice-show', 'InvoiceController@showForCustomer');


//Invoice items part
Route::get('items/select-items', 'InvoiceItemController@selectItems');
Route::get('items', 'InvoiceItemController@index')->middleware('admin');
Route::post('items', 'InvoiceItemController@store')->middleware('admin');
Route::post('items/{item}', 'InvoiceItemController@update')->middleware('admin');
Route::delete('items/destroy/{item}', 'InvoiceItemController@destroy')->middleware('admin');

//invoice email part
Route::get('invoice-mail/create/{invoice}', 'InvoiceMailController@create');
Route::post('invoice-mail/{invoice}', 'InvoiceMailController@store');

//customers part
Route::get('customers', 'CustomerController@index')->middleware('admin');
Route::get('customers/create', 'CustomerController@create');
Route::post('customers', 'CustomerController@store');
Route::post('customers/{customer}', 'CustomerController@update');
Route::get('customers/{customer}/edit', 'CustomerController@edit');
Route::patch('customers/{customer}', 'CustomerController@update');
Route::delete('customers/{customer}', 'CustomerController@destroy');
Route::get('customers/statement-excel/{customer}', 'CustomerController@statementUploadExcel');
Route::get('customers/statement-pdf/{customer}', 'CustomerController@statementUploadPdf');


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
