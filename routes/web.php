<?php

use App\Http\Controllers\ProductController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function ()  {
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products/store', [ProductController::class, 'productStore'])->name('product.store');
    Route::post('/products/delete', [ProductController::class, 'productDelete'])->name('product.delete');
    Route::get('/products/list', [ProductController::class, 'productList'])->name('product.list');
    Route::post('/products/searchproduct', [ProductController::class, 'searchProduct'])->name('product.searchlist');
    Route::post('/products/subcategoryproduct', [ProductController::class, 'subcategoryProduct'])->name('subcategory.product');

    Route::post('/products/filtercat', [ProductController::class, 'filterCategory'])->name('product.filtercat');
    Route::post('/products/price', [ProductController::class, 'filterPrice'])->name('product.price');


});
