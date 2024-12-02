<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OauthController;

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

Route::get('/', function () {
    return view('welcome');
});

// Call only once to authorize
Route::get('/oauth2-callback', [OauthController::class, 'getAuthorization']);

// Test mail sending
Route::post('/mail-test', [OauthController::class, 'mailTest']);

