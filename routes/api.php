<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('user-profile', 'AuthController@userProfile');
    Route::get('accessKey', [InvoiceController::class, 'accessKey']);
    Route::post('createxml', [InvoiceController::class, 'createXml']);
    Route::get('signxml', [InvoiceController::class, 'signXml']);
    Route::get('validatexml', [InvoiceController::class, 'sendInvoiceToSRI']);
    Route::get('verifyxml', [InvoiceController::class, 'verifyInvoice']);
    Route::get('signXmltest', [InvoiceController::class, 'signXmltest']);
    Route::get('test', [InvoiceController::class, 'store']);
    Route::get('secuencial', [InvoiceController::class, 'getNextInvoice']);
    Route::get('index', [MailController::class,'send']);
    Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
    Route::get('products', [ProductController::class, 'getAll']);
    Route::post('add-product', [ProductController::class, 'addProduct']);
    Route::put('edit-product/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('delete-product/{id}',[ ProductController::class, 'delete']);
    Route::get('clients', [ClientController::class, 'getAll']);
    Route::post('add-client', [ClientController::class, 'addReceptor']);
    Route::put('edit-client/{id}', [ClientController::class, 'updateReceptor']);
    Route::delete('delete-client/{id}',[ ClientController::class, 'delete']);
});
