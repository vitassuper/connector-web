<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DealController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Bots\BotController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Exchanges\ExchangeController;

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

Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('bots/{bot}/toggle', [BotController::class, 'toggle'])->name('bots.toggle');
    Route::resource('bots', BotController::class);

    Route::resource('exchanges', ExchangeController::class)->except(['edit', 'update']);

    Route::post('deals/{deal}/close', [DealController::class, 'close'])->name('deals.close');
    Route::post('deals/{deal}/add', [DealController::class, 'add'])->name('deals.add');
    Route::get('deals', [DealController::class, 'index'])->name('deals.index');
});
