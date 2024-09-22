<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\LayoutController;
use App\Http\Controllers\LikeController;

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
  return redirect()->route('index');
});

// Route::get('/layouts', [LayoutController::class, 'index'])->name('index');   
Route::get('/layouts', [LayoutController::class, 'index'])->name('index');
Route::get('/layouts/create', [LayoutController::class, 'create'])->name('layout.create');   
Route::post('/layouts/create', [LayoutController::class, 'store']
    )->name('layout.store')->middleware('auth');
Route::get('/layouts/{layout}', [LayoutController::class, 'edit'])->name('layout.edit');
Route::put('/layouts/{layout}', [LayoutController::class, 'store']
    )->name('layout.update')->middleware('auth');
    
Route::post('/layouts/{layout}/like', [LikeController::class, 'store']
    )->middleware('auth');    

//*****ここからデフォルトのルーティング*****/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
