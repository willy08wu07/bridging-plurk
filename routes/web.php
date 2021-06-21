<?php

use App\Http\Controllers\Auth\PlurkAuthenticationController;
use App\Http\Controllers\WebController;
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

Route::get('/', WebController::class)
    ->name('dashboard');

Route::post('/plurks', [WebController::class, 'postNewPlurk']);

Route::get('/user/login/plurk', PlurkAuthenticationController::class)
    ->name('login');
Route::post('/user/login/plurk', [PlurkAuthenticationController::class, 'redirectToPlurkOAuth']);
Route::get('/user/login/plurk/auth', [PlurkAuthenticationController::class, 'store']);
Route::get('/user/logout', [PlurkAuthenticationController::class, 'destroy']);
