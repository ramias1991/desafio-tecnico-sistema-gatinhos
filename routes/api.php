<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cats', [CatController::class, 'list'])->middleware('apiJwt');

Route::post('/cats-front', [CatController::class, 'search']);

Route::post('/cats', [CatController::class, 'searchBack']);

Route::post('/add-cat', [CatController::class, 'addCatBack']);

Route::post('/edit-cat-front', [CatController::class, 'editCatAction']);

Route::put('/edit-cat', [CatController::class, 'editCatBack']);

Route::delete('/delete-cat/{id_cat}', [CatController::class, 'deleteCatBack']);

Route::delete('/delete-all', [CatController::class, 'cleanAllCatsBack']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/cats-api', [CatController::class, 'listApi']);
