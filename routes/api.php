<?php

use App\Http\Controllers\PropertyMatchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(PropertyMatchController::class)->group(function() {
    Route::get('/match/{propertyId}', 'match')->name('api.match');
});
