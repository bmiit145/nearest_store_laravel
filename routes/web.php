<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homeController;
use App\Models\Store;
use App\Http\Controllers\adminController;
use App\Http\Controllers\StoreController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [homeController::class, 'index']);
Route::post('/findNearestStore', [StoreController::class, 'findNearestStore'])->name('find_nearest_store');
Route::get('/createStore' , function () {
    $store = new Store();
    $store->store_name = 'Store 1';
    $store->store_address = "Kamrej";
    $store->latitute = "21.263622";
    $store->longitute = "72.977287";
    $store->save();
})->name('create_store');

// admin routes
Route::get('/admin' , [adminController::class , 'index'])->name('admin');
Route::post('/addStoreForm' , [StoreController::class , 'addStoreForm'])->name('add_store_form');
