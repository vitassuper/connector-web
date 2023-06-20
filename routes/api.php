<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Bots\ApiBotController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('bots', [ApiBotController::class, 'index'])->name('api.bots.index');
});
