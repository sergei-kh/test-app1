<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;

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

Route::get('/order/product-info/{id}', [OrderController::class, 'info']);
Route::resource('order', OrderController::class)->only(['store', 'show', 'update']);
Route::resource('report', ReportController::class)->only(['index']);
Route::resource('product', ProductController::class)->only(['index']);
