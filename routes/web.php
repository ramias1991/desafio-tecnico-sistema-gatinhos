<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CatController;

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

$routeDefault = '/cats';

Route::redirect('/', $routeDefault);

Route::get($routeDefault, [CatController::class, 'index'])->name('home')->middleware('auth');
Route::post($routeDefault, [CatController::class, 'search']);

Route::get('/delete-cat/{id_cat}', [CatController::class, 'deleteCat'])->name('delete-cat');

Route::get('/edit-cat/{id_cat}', [CatController::class, 'editCat'])->name('edit-cat')->middleware('auth');
Route::post('/edit-cat/{id_cat}', [CatController::class, 'editCatAction']);

Route::get('/clean-all', [CatController::class, 'cleanAllCats'])->name('clean-all');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/logout/{msg}', [LoginController::class, 'logout'])->name('logout');
Route::post('logout', [LoginController::class, 'logout']);

Route::fallback(function(){
    return view('404');
});

