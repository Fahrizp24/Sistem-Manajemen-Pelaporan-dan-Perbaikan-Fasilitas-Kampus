<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
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

Route::pattern('id', '[0-9]+');//ketika ada parametr {id}, maka harus berupa angka

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix'=>'admin'],function () {
    
});

Route::group(['prefix'=>'sarpras'],function () {
    
});

Route::group(['prefix'=>'teknisi'],function () {
    
});

// Group route khusus tendik, dosen, dan mahasiswa
Route::group(['prefix'=>'pelapor'],function () {
    
});