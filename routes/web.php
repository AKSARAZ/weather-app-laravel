<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController; // Import controller

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

// Route untuk menampilkan halaman utama dengan form pencarian
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');

// Route untuk memproses permintaan cuaca
Route::post('/get-weather', [WeatherController::class, 'getWeather'])->name('weather.get');