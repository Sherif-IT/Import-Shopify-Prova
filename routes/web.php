<?php

use App\Http\Controllers\Import\Laravel\ImportController as LaravelImportController;
use App\Http\Controllers\Import\Shopify\ImportController as ShopifyImportController;
use Illuminate\Support\Facades\Route;

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

Route::get('/import-products-from-sheet', [LaravelImportController::class, 'importProductsFromSheet']);

Route::get('/import-products-to-shopify', [ShopifyImportController::class, 'importProductsToShopify']);
