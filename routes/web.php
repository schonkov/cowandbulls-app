<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/games', [GameController::class, 'index'])->middleware(['auth'])->name('game.index');
Route::get('/game/topplayers', [GameController::class, 'topPlayers'])->middleware(['auth'])->name('game.topplayers');
Route::get('/game/new', [GameController::class, 'newGame'])->middleware(['auth'])->name('game.new');
Route::post('/game/new', [GameController::class, 'newGame'])->middleware(['auth'])->name('game.new');
Route::get('/game/{id}', [GameController::class, 'show'])->middleware(['auth'])->name('game.show');
Route::post('/game/{id}/guess', [GameController::class, 'userGuess'])->middleware(['auth'])->name('game.guess');
Route::post('/game/end', [GameController::class, 'endGame'])->name('game.end');

require __DIR__.'/auth.php';

