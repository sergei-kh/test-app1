<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

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

Route::get('/', [PageController::class, 'index'])->name('main');
Route::get('/report', [PageController::class, 'report'])->name('report');

Route::get('/order/product-info/{id}', [OrderController::class, 'info']);
Route::resource('order', OrderController::class)->only(['store', 'show', 'update']);

Route::resource('product', ProductController::class)->only(['index']);
